@extends("layouts.app")
@section("content")
<div class="container">
    <h1 class="mt-3 mb-3">Ajouter un etudiant</h1>
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-body">
                    <form role="form" method="post" action="{{ url('/student/add') }}">
                        {!! csrf_field() !!}

                        <div class="form-group">
                            <label for="f_name">Prenom</label>
                            <input id="f_name" type="text" class="form-control" name="firstname" required autofocus>
                        </div>

                        <div class="form-group">
                            <label for="l_name">Nom</label>
                            <input id="l_name" type="text" class="form-control" name="lastname" required>
                        </div>

                        <div class="form-group">
                            <label for="promotion">Promotion</label>
                            <select id="promotion" class="form-control" name="promotion" required>
                                <option disabled selected hidden>Choisir une promotion</option>
                                @foreach($promotions as $promotion)
                                    <option value="{{ $promotion->id }}">{{ $promotion->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                Ajouter un etudiant
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection