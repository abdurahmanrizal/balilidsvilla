@extends('layouts.admin.app')
@section('content')
      <style>
          .overview-chart {
              height: 55px !important;
          }
      </style>
      <!-- MAIN CONTENT-->
      <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="overview-wrap">
                            <h2 class="title-1">Overview</h2>
                        </div>
                    </div>
                </div>
                <div class="row m-t-25">
                    <div class="col-sm-6 col-lg-3">
                        <div class="overview-item overview-item--c1">
                            <div class="overview__inner">
                                <div class="overview-box clearfix">
                                    <div class="icon">
                                        <i class="zmdi zmdi-hotel"></i>
                                    </div>
                                    <div class="text">
                                        <h2>{{$sum_villas}}</h2>
                                        <span>Villas</span>
                                    </div>
                                </div>
                                <div class="overview-chart">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="overview-item overview-item--c2">
                            <div class="overview__inner">
                                <div class="overview-box clearfix">
                                    <div class="icon">
                                        <i class="zmdi zmdi-label"></i>
                                    </div>
                                    <div class="text">
                                        <h2>{{$sum_activities}}</h2>
                                        <span>Activities</span>
                                    </div>
                                </div>
                                <div class="overview-chart">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="overview-item overview-item--c3">
                            <div class="overview__inner">
                                <div class="overview-box clearfix">
                                    <div class="icon">
                                        <i class="zmdi zmdi-dropbox"></i>
                                    </div>
                                    <div class="text">
                                        <h2>{{$sum_packages}}</h2>
                                        <span>Packages</span>
                                    </div>
                                </div>
                                <div class="overview-chart">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
