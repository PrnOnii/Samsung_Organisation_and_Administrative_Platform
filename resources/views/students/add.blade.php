<?php
/**
 * Created by PhpStorm.
 * User: kevan
 * Date: 5/18/18
 * Time: 6:47 PM
 */ ?>

@extends("layouts.app")
@section("content")
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="panel panel-default">
                    <div class="panel-heading"><h1>New Student</h1></div>
                    <div class="panel-body">
                        <form role="form" method="post" action="/student/add">
                            {!! csrf_field() !!}

                            <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                                <label for="f_name" class="col-12 control-label">First Name</label>

                                <div class="col-12">
                                    <input id="f_name" type="text" class="form-control" name="firstname" required autofocus>

                                    @if ($errors->has('first_name'))
                                        <span class="help-block">
                                <strong>{{ $errors->first('first_name') }}</strong>
                            </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                                <label for="l_name" class="col-12 control-label">Last Name</label>

                                <div class="col-12">
                                    <input id="l_name" type="text" class="form-control" name="lastname" required autofocus>

                                    @if ($errors->has('last_name'))
                                        <span class="help-block">
                                <strong>{{ $errors->first('last_name') }}</strong>
                            </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('promo') ? ' has-error' : '' }}">
                                <label for="promotion" class="col-12 control-label">Promotion</label>

                                <div class="col-12">
                                    <select id="promotion" class="form-control" name="promotion" required>
                                        <option value="0">Choose a Promotion...</option>
                                        @foreach($promotions as $promotion)
                                            <option value="{{ $promotion->id }}">{{ $promotion->name }}</option>
                                        @endforeach
                                    </select>

                                    @if ($errors->has('promo'))
                                        <span class="help-block">
                                <strong>{{ $errors->first('promo') }}</strong>
                            </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        Add Student
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection