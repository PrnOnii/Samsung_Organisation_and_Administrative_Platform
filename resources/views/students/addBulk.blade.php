@extends("layouts.app")
@section("content")
<div class="container">
    <h1 class="mt-3 mb-3">Ajouter plusieurs etudiants</h1>
    <div class="row">
        <div class="col-8 offset-2">
            <div class="card">
                <div class="card-body">
                    <form role="form" method="post" action="{{ url('/student/addBulk') }}">
                        {!! csrf_field() !!}

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif  

                        <div class="form-group">
                            <div class="alert alert-primary" role="alert">
                                Ajoutez les etudiants sous la forme <code>nom prenom</code> suivi d'un retour a la ligne.
                            </div>
                        </div>

                        <div class="form-group">
                            <textarea id="names" rows="5" type="text" class="form-control" name="names" required autofocus></textarea>
                        </div>

                        <div class="form-group">
                            <label for="promotion">Promotion</label>
                            <select id="promotion" class="form-control" name="promotion" required>
                                <option value="0">Choisir une promotion</option>
                                @foreach($promotions as $promotion)
                                    <option value="{{ $promotion->id }}">{{ $promotion->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                Ajouter les etudiants
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection