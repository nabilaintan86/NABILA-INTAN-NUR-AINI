<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Note;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class DeviceNoteController extends Controller
{
    public function store(Request $request, Device $device)
    {
        if (! $request->user()) {
            abort(401);
        }

        Gate::authorize('create', Note::class);

        $validated = $this->validateNote($request);
        $note = $device->notes()->make($validated);
        $note->user_id = auth()->id();
        $note->save();

        return redirect()
            ->route('admin.device.detail', $device)
            ->with('success', 'Catatan perangkat berhasil ditambahkan.');
    }

    public function storeFromReport(Request $request)
    {
        if (! $request->user()) {
            abort(401);
        }

        Gate::authorize('create', Note::class);

        $validated = $this->validateNote($request, true, true);
        $device = Device::findOrFail($validated['device_id']);

        $note = $device->notes()->make($validated);
        $note->user_id = auth()->id();
        $note->save();

        return redirect()
            ->route('admin.device.notes.report')
            ->with('success', 'Catatan perangkat berhasil ditambahkan.');
    }

    public function update(Request $request, Note $note)
    {
        if (! $request->user()) {
            abort(401);
        }

        Gate::authorize('update', $note);

        $note->update($this->validateNote($request, false));

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Catatan berhasil diperbarui.']);
        }

        return redirect()
            ->back()
            ->with('success', 'Catatan berhasil diperbarui.');
    }

    public function destroy(Request $request, Note $note)
    {
        Gate::authorize('delete', $note);

        $device = $note->device;
        $note->delete();

        if ($request->expectsJson()) {
            return response()->noContent();
        }

        return redirect()
            ->route('admin.device.detail', $device)
            ->with('success', 'Catatan berhasil dihapus.');
    }

    public function report(Request $request)
    {
        Gate::authorize('viewAny', Note::class);

        $filters = [
            'q' => $this->stringValue($request->query('q', '')),
            'device_id' => $this->positiveInteger($request->query('device_id')),
            'user_id' => $this->positiveInteger($request->query('user_id')),
            'priority' => $this->normalizePriorityFilter($request->query('priority', $request->query('status'))),
            'date_from' => $this->dateValue($request->query('date_from', $request->query('from'))),
            'date_to' => $this->dateValue($request->query('date_to', $request->query('to'))),
            'sort' => $request->query('sort') === 'oldest' ? 'oldest' : 'latest',
        ];

        if (! Schema::hasTable('device_notes')) {
            $notes = new LengthAwarePaginator([], 0, 20, 1, [
                'path' => $request->url(),
                'query' => $request->query(),
            ]);
            $urgentCount = 0;
            $normalCount = 0;
            $totalCount = 0;
        } else {
            $query = $this->filteredNotes($filters);
            $notes = $query->paginate(20)->withQueryString();
            $urgentCount = (clone $query)->where('priority', 'Urgent')->count();
            $normalCount = (clone $query)->where('priority', 'Normal')->count();
            $totalCount = (clone $query)->count();
        }

        $devices = Schema::hasTable('devices')
            ? Device::query()->orderBy('name')->get(['id', 'name'])
            : collect();
        $users = Schema::hasTable('users')
            ? User::query()->orderBy('name')->get(['id', 'name'])
            : collect();

        return view('admin.device-note-report', [
            'notes' => $notes,
            'filters' => $filters,
            'devices' => $devices,
            'users' => $users,
            'urgentCount' => $urgentCount,
            'normalCount' => $normalCount,
            'totalCount' => $totalCount,
        ]);
    }

    protected function validateNote(Request $request, bool $includeDevice = false, bool $requireDevice = false): array
    {
        $this->prepareNoteInput($request);

        $rules = [
            'tanggal' => ['required', 'date'],
            'catatan' => ['required', 'string', 'max:5000'],
            'priority' => ['nullable', Rule::in(['Normal', 'Urgent'])],
        ];

        if ($includeDevice) {
            $rules['device_id'] = [
                $requireDevice ? 'required' : 'nullable',
                'integer',
                'exists:devices,id',
            ];
        }

        return $request->validate($rules);
    }

    protected function prepareNoteInput(Request $request): void
    {
        $tanggal = $request->input('tanggal');
        $priority = $request->input('priority', $request->input('status', 'Normal'));
        $catatan = $request->input('catatan', $request->input('note'));

        $request->merge([
            'tanggal' => $tanggal === null || $tanggal === '' ? now() : $tanggal,
            'priority' => $this->normalizePriority($priority),
            'catatan' => $catatan,
        ]);
    }

    protected function normalizePriority(mixed $value): string
    {
        if (! is_scalar($value) || trim((string) $value) === '') {
            return 'Normal';
        }

        $value = strtolower(trim((string) $value));

        return match ($value) {
            'urgent', 'tinggi', 'high', 'penting' => 'Urgent',
            default => 'Normal',
        };
    }

    protected function normalizePriorityFilter(mixed $value): ?string
    {
        if (! is_scalar($value) || trim((string) $value) === '') {
            return null;
        }

        return $this->normalizePriority($value);
    }

    protected function filteredNotes(array $filters): Builder
    {
        $deviceColumns = ['id', 'name'];
        foreach (['ip_address', 'type', 'icon_type', 'status', 'protocol'] as $column) {
            if (Schema::hasColumn('devices', $column)) {
                $deviceColumns[] = $column;
            }
        }

        // Eager loading user (pembuat) & device: tidak ada N+1 di tabel report.
        $userColumns = ['id', 'name', 'email', 'role'];
        if (Schema::hasColumn('users', 'avatar')) {
            $userColumns[] = 'avatar';
        }

        $query = Note::query()->with([
            'device:'.implode(',', $deviceColumns),
            'user:'.implode(',', $userColumns),
        ]);

        if ($filters['device_id']) {
            $query->where('device_id', $filters['device_id']);
        }

        if ($filters['user_id']) {
            $query->where('user_id', $filters['user_id']);
        }

        if ($filters['priority']) {
            $query->where('priority', $filters['priority']);
        }

        if ($filters['date_from']) {
            $query->whereDate('tanggal', '>=', $filters['date_from']);
        }

        if ($filters['date_to']) {
            $query->whereDate('tanggal', '<=', $filters['date_to']);
        }

        if ($filters['q'] !== '') {
            $keyword = '%'.$filters['q'].'%';
            $query->where(function (Builder $nested) use ($keyword): void {
                $nested->where('catatan', 'like', $keyword)
                    ->orWhereHas('device', fn (Builder $device) => $device->where('name', 'like', $keyword))
                    ->orWhereHas('user', fn (Builder $user) => $user->where('name', 'like', $keyword));
            });
        }

        if ($filters['sort'] === 'oldest') {
            return $query->orderBy('tanggal')->orderBy('id');
        }

        return $query->orderByDesc('tanggal')->orderByDesc('id');
    }

    protected function positiveInteger($value): ?int
    {
        if (! is_scalar($value)) {
            return null;
        }

        return filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) ?: null;
    }

    protected function stringValue($value): string
    {
        return is_scalar($value) ? trim((string) $value) : '';
    }

    protected function dateValue($value): ?string
    {
        if (! is_string($value) || ! preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return null;
        }

        return $value;
    }
}
