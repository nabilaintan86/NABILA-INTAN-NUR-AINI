@extends('layouts.admin')
@section('title', 'Data Tables - NioBoard Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-content">
                                <h1 class="nk-block-title">
                                    Datatables
                                </h1>
                                <p>
                                    The tables in this section had use Simple-DataTables plugin. For more info see <a href="https://github.com/fiduswriter/Simple-DataTables" target="_blank">the official github repo</a>.
                                </p>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Example
                                    </h3>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <table class="datatable-init table" data-nk-container="table-responsive table-border">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <span class="overline-title text-nowrap">Name</span>
                                                </th>
                                                <th>
                                                    <span class="overline-title text-nowrap">Position</span>
                                                </th>
                                                <th>
                                                    <span class="overline-title text-nowrap">Office</span>
                                                </th>
                                                <th>
                                                    <span class="overline-title text-nowrap">Age</span>
                                                </th>
                                                <th>
                                                    <span class="overline-title text-nowrap">Start date</span>
                                                </th>
                                                <th>
                                                    <span class="overline-title text-nowrap">Salary</span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    Tiger Nixon
                                                </td>
                                                <td>
                                                    System Architect
                                                </td>
                                                <td>
                                                    Edinburgh
                                                </td>
                                                <td>
                                                    61
                                                </td>
                                                <td>
                                                    2011/04/25
                                                </td>
                                                <td>
                                                    $320,800
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Garrett Winters
                                                </td>
                                                <td>
                                                    Accountant
                                                </td>
                                                <td>
                                                    Tokyo
                                                </td>
                                                <td>
                                                    63
                                                </td>
                                                <td>
                                                    2011/07/25
                                                </td>
                                                <td>
                                                    $170,750
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Ashton Cox
                                                </td>
                                                <td>
                                                    Junior Technical Author
                                                </td>
                                                <td>
                                                    San Francisco
                                                </td>
                                                <td>
                                                    66
                                                </td>
                                                <td>
                                                    2009/01/12
                                                </td>
                                                <td>
                                                    $86,000
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Cedric Kelly
                                                </td>
                                                <td>
                                                    Senior Javascript Developer
                                                </td>
                                                <td>
                                                    Edinburgh
                                                </td>
                                                <td>
                                                    22
                                                </td>
                                                <td>
                                                    2012/03/29
                                                </td>
                                                <td>
                                                    $433,060
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Airi Satou
                                                </td>
                                                <td>
                                                    Accountant
                                                </td>
                                                <td>
                                                    Tokyo
                                                </td>
                                                <td>
                                                    33
                                                </td>
                                                <td>
                                                    2008/11/28
                                                </td>
                                                <td>
                                                    $162,700
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Brielle Williamson
                                                </td>
                                                <td>
                                                    Integration Specialist
                                                </td>
                                                <td>
                                                    New York
                                                </td>
                                                <td>
                                                    61
                                                </td>
                                                <td>
                                                    2012/12/02
                                                </td>
                                                <td>
                                                    $372,000
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Herrod Chandler
                                                </td>
                                                <td>
                                                    Sales Assistant
                                                </td>
                                                <td>
                                                    San Francisco
                                                </td>
                                                <td>
                                                    59
                                                </td>
                                                <td>
                                                    2012/08/06
                                                </td>
                                                <td>
                                                    $137,500
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Rhona Davidson
                                                </td>
                                                <td>
                                                    Integration Specialist
                                                </td>
                                                <td>
                                                    Tokyo
                                                </td>
                                                <td>
                                                    55
                                                </td>
                                                <td>
                                                    2010/10/14
                                                </td>
                                                <td>
                                                    $327,900
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Colleen Hurst
                                                </td>
                                                <td>
                                                    Javascript Developer
                                                </td>
                                                <td>
                                                    San Francisco
                                                </td>
                                                <td>
                                                    39
                                                </td>
                                                <td>
                                                    2009/09/15
                                                </td>
                                                <td>
                                                    $205,500
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Sonya Frost
                                                </td>
                                                <td>
                                                    Software Engineer
                                                </td>
                                                <td>
                                                    Edinburgh
                                                </td>
                                                <td>
                                                    23
                                                </td>
                                                <td>
                                                    2008/12/13
                                                </td>
                                                <td>
                                                    $103,600
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Jena Gaines
                                                </td>
                                                <td>
                                                    Office Manager
                                                </td>
                                                <td>
                                                    London
                                                </td>
                                                <td>
                                                    30
                                                </td>
                                                <td>
                                                    2008/12/19
                                                </td>
                                                <td>
                                                    $90,560
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Sonya Frost
                                                </td>
                                                <td>
                                                    Software Engineer
                                                </td>
                                                <td>
                                                    Edinburgh
                                                </td>
                                                <td>
                                                    23
                                                </td>
                                                <td>
                                                    2008/12/13
                                                </td>
                                                <td>
                                                    $103,600
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Jena Gaines
                                                </td>
                                                <td>
                                                    Office Manager
                                                </td>
                                                <td>
                                                    London
                                                </td>
                                                <td>
                                                    30
                                                </td>
                                                <td>
                                                    2008/12/19
                                                </td>
                                                <td>
                                                    $90,560
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Sonya Frost
                                                </td>
                                                <td>
                                                    Software Engineer
                                                </td>
                                                <td>
                                                    Edinburgh
                                                </td>
                                                <td>
                                                    23
                                                </td>
                                                <td>
                                                    2008/12/13
                                                </td>
                                                <td>
                                                    $103,600
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Jena Gaines
                                                </td>
                                                <td>
                                                    Office Manager
                                                </td>
                                                <td>
                                                    London
                                                </td>
                                                <td>
                                                    30
                                                </td>
                                                <td>
                                                    2008/12/19
                                                </td>
                                                <td>
                                                    $90,560
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Sonya Frost
                                                </td>
                                                <td>
                                                    Software Engineer
                                                </td>
                                                <td>
                                                    Edinburgh
                                                </td>
                                                <td>
                                                    23
                                                </td>
                                                <td>
                                                    2008/12/13
                                                </td>
                                                <td>
                                                    $103,600
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Jena Gaines
                                                </td>
                                                <td>
                                                    Office Manager
                                                </td>
                                                <td>
                                                    London
                                                </td>
                                                <td>
                                                    30
                                                </td>
                                                <td>
                                                    2008/12/19
                                                </td>
                                                <td>
                                                    $90,560
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Sonya Frost
                                                </td>
                                                <td>
                                                    Software Engineer
                                                </td>
                                                <td>
                                                    Edinburgh
                                                </td>
                                                <td>
                                                    23
                                                </td>
                                                <td>
                                                    2008/12/13
                                                </td>
                                                <td>
                                                    $103,600
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="form-note mt-3">
                                        Use <code>.datatable-init</code> class to <code>table</code> to enable data-tables mode.
                                        <br>
                                        <strong class="text-black">Note:</strong> If you want to use datatable another page then you must have to load datatables's js file. then you can use datatable to your project.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Datetime Sorting
                                    </h3>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <table class="datatable-init table" data-nk-container="table-responsive table-border">
                                        <thead>
                                            <tr>
                                                <th class="text-nowrap" data-type="date" data-format="ISO_8601">
                                                    <span class="overline-title">ISO 8601</span>
                                                </th>
                                                <th class="text-nowrap" data-type="date" data-format="MYSQL">
                                                    <span class="overline-title">MySQL</span>
                                                </th>
                                                <th class="text-nowrap" data-type="date" data-format="RFC_2822">
                                                    <span class="overline-title">RFC 2822</span>
                                                </th>
                                                <th class="text-nowrap" data-type="date" data-format="MMM DD, YYYY">
                                                    <span class="overline-title">MMM DD, YYYY</span>
                                                </th>
                                                <th class="text-nowrap" data-type="date" data-format="DD/MM/YYYY">
                                                    <span class="overline-title">DD/MM/YYYY</span>
                                                </th>
                                                <th class="text-nowrap" data-type="date" data-format="MM-DD-YY">
                                                    <span class="overline-title">MM-DD-YY</span>
                                                </th>
                                                <th class="text-nowrap" data-type="date" data-format="UNIX">
                                                    <span class="overline-title">UNIX</span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    2013-06-27T19:10:21-07:00
                                                </td>
                                                <td>
                                                    1994-12-06 22:56:36
                                                </td>
                                                <td>
                                                    Tue, 14 Mar 1995 07:11:46 -0800
                                                </td>
                                                <td>
                                                    Dec 8, 2001
                                                </td>
                                                <td>
                                                    19/10/2016
                                                </td>
                                                <td>
                                                    07-23-12
                                                </td>
                                                <td>
                                                    853102101
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    1980-09-12T02:50:23-07:00
                                                </td>
                                                <td>
                                                    1997-11-23 07:06:45
                                                </td>
                                                <td>
                                                    Mon, 21 Nov 2005 04:23:35 -0800
                                                </td>
                                                <td>
                                                    Aug 19, 1997
                                                </td>
                                                <td>
                                                    27/05/1981
                                                </td>
                                                <td>
                                                    01-13-17
                                                </td>
                                                <td>
                                                    713671686
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    1994-10-27T12:11:30-07:00
                                                </td>
                                                <td>
                                                    2001-06-22 09:21:47
                                                </td>
                                                <td>
                                                    Sat, 30 Apr 2005 03:36:38 -0700
                                                </td>
                                                <td>
                                                    Aug 1, 1984
                                                </td>
                                                <td>
                                                    04/11/2011
                                                </td>
                                                <td>
                                                    04-25-11
                                                </td>
                                                <td>
                                                    1408349475
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    2015-05-20T15:38:00-07:00
                                                </td>
                                                <td>
                                                    1990-12-20 10:13:03
                                                </td>
                                                <td>
                                                    Thu, 16 Jan 2003 16:38:36 -0800
                                                </td>
                                                <td>
                                                    Aug 19, 1982
                                                </td>
                                                <td>
                                                    05/11/1989
                                                </td>
                                                <td>
                                                    03-06-18
                                                </td>
                                                <td>
                                                    677441387
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    1982-07-05T06:31:38-07:00
                                                </td>
                                                <td>
                                                    1989-11-18 13:43:58
                                                </td>
                                                <td>
                                                    Thu, 09 Nov 2000 10:25:09 -0800
                                                </td>
                                                <td>
                                                    Oct 11, 2003
                                                </td>
                                                <td>
                                                    16/07/1992
                                                </td>
                                                <td>
                                                    10-29-11
                                                </td>
                                                <td>
                                                    523469491
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    2008-08-11T01:01:32-07:00
                                                </td>
                                                <td>
                                                    1981-02-16 19:29:34
                                                </td>
                                                <td>
                                                    Thu, 18 Dec 2003 07:16:19 -0800
                                                </td>
                                                <td>
                                                    May 16, 1981
                                                </td>
                                                <td>
                                                    30/04/1989
                                                </td>
                                                <td>
                                                    05-24-87
                                                </td>
                                                <td>
                                                    1141495716
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    1982-11-28T18:34:32-08:00
                                                </td>
                                                <td>
                                                    2002-02-22 04:36:38
                                                </td>
                                                <td>
                                                    Fri, 11 Mar 1994 01:16:08 -0800
                                                </td>
                                                <td>
                                                    Apr 2, 2014
                                                </td>
                                                <td>
                                                    12/10/2008
                                                </td>
                                                <td>
                                                    05-01-90
                                                </td>
                                                <td>
                                                    464746558
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    1996-07-29T13:05:44-07:00
                                                </td>
                                                <td>
                                                    1993-03-15 18:27:14
                                                </td>
                                                <td>
                                                    Fri, 06 Jun 2003 07:53:59 -0700
                                                </td>
                                                <td>
                                                    Jul 16, 2004
                                                </td>
                                                <td>
                                                    14/02/1995
                                                </td>
                                                <td>
                                                    02-08-96
                                                </td>
                                                <td>
                                                    405430252
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    1987-03-02T20:03:45-08:00
                                                </td>
                                                <td>
                                                    1990-01-09 10:26:10
                                                </td>
                                                <td>
                                                    Thu, 15 Sep 2005 13:12:25 -0700
                                                </td>
                                                <td>
                                                    Nov 9, 2005
                                                </td>
                                                <td>
                                                    14/06/1983
                                                </td>
                                                <td>
                                                    11-19-13
                                                </td>
                                                <td>
                                                    1310935236
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    2014-04-02T01:20:31-07:00
                                                </td>
                                                <td>
                                                    1995-03-19 17:07:42
                                                </td>
                                                <td>
                                                    Sat, 11 May 2013 04:14:36 -0700
                                                </td>
                                                <td>
                                                    Jun 11, 2000
                                                </td>
                                                <td>
                                                    17/02/2001
                                                </td>
                                                <td>
                                                    05-03-15
                                                </td>
                                                <td>
                                                    636464866
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    1984-01-18T00:39:34-08:00
                                                </td>
                                                <td>
                                                    2015-12-11 13:14:55
                                                </td>
                                                <td>
                                                    Fri, 25 Jan 2013 21:09:50 -0800
                                                </td>
                                                <td>
                                                    Aug 15, 2013
                                                </td>
                                                <td>
                                                    11/02/2005
                                                </td>
                                                <td>
                                                    11-29-93
                                                </td>
                                                <td>
                                                    922286213
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    2012-09-04T15:14:23-07:00
                                                </td>
                                                <td>
                                                    1990-01-22 12:37:32
                                                </td>
                                                <td>
                                                    Sat, 01 May 1999 08:20:37 -0700
                                                </td>
                                                <td>
                                                    Jan 27, 1999
                                                </td>
                                                <td>
                                                    02/02/2010
                                                </td>
                                                <td>
                                                    12-04-06
                                                </td>
                                                <td>
                                                    545366064
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    1988-03-14T11:42:05-08:00
                                                </td>
                                                <td>
                                                    1992-10-11 21:13:23
                                                </td>
                                                <td>
                                                    Tue, 16 Sep 2008 10:32:44 -0700
                                                </td>
                                                <td>
                                                    May 28, 2012
                                                </td>
                                                <td>
                                                    15/08/2003
                                                </td>
                                                <td>
                                                    02-21-90
                                                </td>
                                                <td>
                                                    896785729
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    1983-06-01T05:28:43-07:00
                                                </td>
                                                <td>
                                                    1999-07-12 06:19:13
                                                </td>
                                                <td>
                                                    Tue, 16 Nov 1999 21:31:30 -0800
                                                </td>
                                                <td>
                                                    Nov 30, 2008
                                                </td>
                                                <td>
                                                    23/01/2004
                                                </td>
                                                <td>
                                                    03-17-08
                                                </td>
                                                <td>
                                                    1422578713
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    1980-08-06T05:20:08-07:00
                                                </td>
                                                <td>
                                                    1986-09-14 19:36:53
                                                </td>
                                                <td>
                                                    Fri, 29 Oct 1999 22:21:35 -0700
                                                </td>
                                                <td>
                                                    May 28, 2014
                                                </td>
                                                <td>
                                                    23/01/1983
                                                </td>
                                                <td>
                                                    03-13-04
                                                </td>
                                                <td>
                                                    1459690948
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    2014-02-15T19:50:40-08:00
                                                </td>
                                                <td>
                                                    1997-11-11 15:45:24
                                                </td>
                                                <td>
                                                    Tue, 07 Jun 1988 16:20:04 -0700
                                                </td>
                                                <td>
                                                    Oct 17, 1992
                                                </td>
                                                <td>
                                                    04/12/1993
                                                </td>
                                                <td>
                                                    10-10-08
                                                </td>
                                                <td>
                                                    1349969151
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    2007-02-08T13:38:13-08:00
                                                </td>
                                                <td>
                                                    2010-01-05 22:22:21
                                                </td>
                                                <td>
                                                    Mon, 18 Sep 2017 01:18:07 -0700
                                                </td>
                                                <td>
                                                    Apr 29, 1999
                                                </td>
                                                <td>
                                                    16/08/1994
                                                </td>
                                                <td>
                                                    06-30-84
                                                </td>
                                                <td>
                                                    756326365
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    2014-02-03T17:32:42-08:00
                                                </td>
                                                <td>
                                                    1989-06-20 22:14:41
                                                </td>
                                                <td>
                                                    Mon, 20 Apr 1981 04:11:15 -0800
                                                </td>
                                                <td>
                                                    Jan 17, 1993
                                                </td>
                                                <td>
                                                    16/07/2015
                                                </td>
                                                <td>
                                                    09-20-15
                                                </td>
                                                <td>
                                                    688566388
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    2015-03-30T05:58:40-07:00
                                                </td>
                                                <td>
                                                    2010-10-31 08:34:15
                                                </td>
                                                <td>
                                                    Tue, 26 May 1998 19:03:54 -0700
                                                </td>
                                                <td>
                                                    Jan 12, 2012
                                                </td>
                                                <td>
                                                    13/08/1988
                                                </td>
                                                <td>
                                                    11-27-09
                                                </td>
                                                <td>
                                                    660566468
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    1992-07-03T09:37:26-07:00
                                                </td>
                                                <td>
                                                    1996-01-19 16:42:48
                                                </td>
                                                <td>
                                                    Wed, 06 Apr 2005 16:01:04 -0700
                                                </td>
                                                <td>
                                                    Aug 19, 1994
                                                </td>
                                                <td>
                                                    10/10/1990
                                                </td>
                                                <td>
                                                    12-03-10
                                                </td>
                                                <td>
                                                    797131280
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    2004-03-01T04:34:57-08:00
                                                </td>
                                                <td>
                                                    1982-10-26 19:46:32
                                                </td>
                                                <td>
                                                    Fri, 23 May 2008 11:31:53 -0700
                                                </td>
                                                <td>
                                                    Apr 27, 2018
                                                </td>
                                                <td>
                                                    30/03/2000
                                                </td>
                                                <td>
                                                    09-15-13
                                                </td>
                                                <td>
                                                    1012207791
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    1984-01-23T01:49:39-08:00
                                                </td>
                                                <td>
                                                    2003-06-12 23:12:34
                                                </td>
                                                <td>
                                                    Wed, 07 May 2014 13:14:43 -0700
                                                </td>
                                                <td>
                                                    May 22, 1981
                                                </td>
                                                <td>
                                                    14/12/1994
                                                </td>
                                                <td>
                                                    05-04-88
                                                </td>
                                                <td>
                                                    411303318
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    2000-02-01T08:26:03-08:00
                                                </td>
                                                <td>
                                                    2013-08-17 17:08:56
                                                </td>
                                                <td>
                                                    Sun, 16 Feb 2014 08:49:40 -0800
                                                </td>
                                                <td>
                                                    Sep 14, 1991
                                                </td>
                                                <td>
                                                    05/01/1998
                                                </td>
                                                <td>
                                                    09-03-00
                                                </td>
                                                <td>
                                                    1230907590
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    1990-04-28T11:08:20-07:00
                                                </td>
                                                <td>
                                                    2016-04-15 18:30:45
                                                </td>
                                                <td>
                                                    Mon, 30 Jan 1995 17:37:31 -0800
                                                </td>
                                                <td>
                                                    Nov 5, 1998
                                                </td>
                                                <td>
                                                    26/10/2015
                                                </td>
                                                <td>
                                                    08-04-99
                                                </td>
                                                <td>
                                                    506528026
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    2017-05-30T18:51:39-07:00
                                                </td>
                                                <td>
                                                    2008-10-16 08:24:15
                                                </td>
                                                <td>
                                                    Fri, 24 May 2002 16:37:40 -0700
                                                </td>
                                                <td>
                                                    Jul 31, 2008
                                                </td>
                                                <td>
                                                    05/07/1992
                                                </td>
                                                <td>
                                                    10-13-08
                                                </td>
                                                <td>
                                                    1469041684
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    1997-09-23T16:45:18-07:00
                                                </td>
                                                <td>
                                                    1980-10-18 17:44:41
                                                </td>
                                                <td>
                                                    Mon, 01 May 1995 19:59:01 -0700
                                                </td>
                                                <td>
                                                    Nov 4, 2000
                                                </td>
                                                <td>
                                                    25/12/1989
                                                </td>
                                                <td>
                                                    07-19-12
                                                </td>
                                                <td>
                                                    621984653
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    1995-04-29T23:03:40-07:00
                                                </td>
                                                <td>
                                                    1989-10-25 12:31:30
                                                </td>
                                                <td>
                                                    Tue, 15 Jan 2002 05:54:34 -0800
                                                </td>
                                                <td>
                                                    Jan 6, 2004
                                                </td>
                                                <td>
                                                    14/09/1988
                                                </td>
                                                <td>
                                                    12-06-05
                                                </td>
                                                <td>
                                                    1197629198
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    1992-09-03T16:13:30-07:00
                                                </td>
                                                <td>
                                                    2016-11-02 20:53:12
                                                </td>
                                                <td>
                                                    Sun, 26 Oct 2008 10:23:41 -0700
                                                </td>
                                                <td>
                                                    Feb 20, 2012
                                                </td>
                                                <td>
                                                    24/08/1997
                                                </td>
                                                <td>
                                                    10-09-90
                                                </td>
                                                <td>
                                                    971030263
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    1985-06-14T07:56:01-07:00
                                                </td>
                                                <td>
                                                    2012-08-21 03:36:44
                                                </td>
                                                <td>
                                                    Sat, 14 Jul 1990 09:55:01 -0700
                                                </td>
                                                <td>
                                                    Jul 18, 2006
                                                </td>
                                                <td>
                                                    02/12/1986
                                                </td>
                                                <td>
                                                    07-19-10
                                                </td>
                                                <td>
                                                    790694847
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    2003-07-04T23:31:49-07:00
                                                </td>
                                                <td>
                                                    2011-09-10 03:13:33
                                                </td>
                                                <td>
                                                    Wed, 14 Apr 1999 22:22:13 -0700
                                                </td>
                                                <td>
                                                    Mar 17, 2015
                                                </td>
                                                <td>
                                                    04/04/1997
                                                </td>
                                                <td>
                                                    02-14-17
                                                </td>
                                                <td>
                                                    531923003
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="form-note mt-3">
                                        Use <code>data-type="date"</code> and <code>data-format="ISO_8601"</code> attribute to thead's <code>th</code> to enable datetime sorting.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @push('scripts')
        <script src="{{ asset('admin-assets/assets/js/data-tables/data-tables.js') }}"></script>
    @endpush
