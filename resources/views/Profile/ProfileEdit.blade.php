@include('Components.Partials.Layout.HeaderPage')
@include('Errors.Geral')
<div class="panel panel-default">

    <div class="panel-heading">{{$UserName}} {{$UserLastname}}</div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6" style="padding-bottom: 20px;">
                <h5>Editar Perfil</h5>
                <form method="POST">

                    {!! csrf_field() !!}
                    <input type="hidden" name="id" value="{{ $UserId }}">

                    <div class="row login-form radius-5 padding-all-10">

                        <div class="form-group">
                            <label for="name">Nome</label>
                            <input type="text" name="name" class="form-control" value="{{ $UserName }}">
                        </div>

                        <div class="form-group">
                            <label for="name">Sobrenome</label>
                            <input type="text" name="lastname" class="form-control" value="{{ $UserLastname }}">
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ $UserEmail }}">
                        </div>

                        @if(\App\Http\Controllers\Auth\RolesController::validar('Admin'))
                            <div class="col-xs-4">
                                <div class="inline-checkbox ">
                                    <label for="enable">
                                        Desativar
                                        <input name="enable" class="form-control" value="0" type="checkbox"{{ $Enable }}>
                                    </label>
                                </div>
                            </div>

                            <div class="col-xs-4">
                                <label for="user_roles">Classe</label>
                                <select name="user_roles" class="form-control">
                                    @foreach($RolesLists as $RolesLit)

                                        <option value="{{$RolesLit['id']}}"  {{$UserRole==$RolesLit['name'] ? 'selected="selected"' : ''}} >{{$RolesLit['name']}}</option>

                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xs-4">

                            </div>
                            <br>
                        @endif

                        <div class="form-group col-xs-12">
                            <button type="submit" class="btn btn-success">Atualizar Perfil</button>
                        </div>

                    </div>

                </form>
            </div>

            <div class="col-md-6">
                <h5>Trocar Senha</h5>
                <form method="POST" action="{{asset('/Perfil/Senha')}}">

                    {!! csrf_field() !!}
                    <input type="hidden" name="id" value="{{ $UserId }}">

                    <div class="row login-form radius-5 padding-all-10">

                        <div class="form-group">
                            <label for="password">Senha</label>
                            <input type="password" name="password" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-success">Trocar Senha</button>
                        </div>

                    </div>

                </form>
            </div>

        </div>

        <br>

    </div>

</div>

@include('Components.Partials.Layout.BottomPage')