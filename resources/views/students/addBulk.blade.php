<?php
/**
 * Created by PhpStorm.
 * User: kevan
 * Date: 5/19/18
 * Time: 4:07 PM
 */ ?>

@extends("layouts.app")
@section("content")
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="panel panel-default">
                    <div class="panel-heading"><h1>Add Student in Bulk</h1></div>
                    <div class="panel-body">
                        <form role="form" method="post" action="/student/addBulk">
                            {!! csrf_field() !!}

                            <div class="form-group{{ $errors->has('names') ? ' has-error' : '' }}">
                                <label for="names" class="col-12 control-label">
                                    Names<br>
                                    <p class="text-muted">Add names with the following method : <br>
                                        Lastname Firstname<br>
                                        Lastname Firstname<br>
                                        if there are spaces in the name, replace them with "-"
                                    </p>
                                </label>


                                <div class="col-12">
                                    <textarea id="names" type="text" class="form-control" name="names" required autofocus></textarea>

                                    @if ($errors->has('names'))
                                        <span class="help-block">
                                <strong>{{ $errors->first('names') }}</strong>
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
                                        Add Students
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