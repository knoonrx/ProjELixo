@include('Components.Partials.Layout.HeaderPage')
@include('Errors.Geral')

<div class="panel panel-default">
    <div class="panel-heading"><strong>Entrar:</strong>
        <span><small>Digite seus dados para entrar: {!! (isset($loginAttempts))? "Restam <span class='text-dangera'> $loginAttempts </span> tentativas" : '' !!} </small></span>
    </div>
    <div class="panel-body">

        <form method="POST" action="{{asset('/User/Entrar')}}">
            {!! csrf_field() !!}

            <div class="row login-form radius-5 padding-all-10">

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Seu Email" value="{{ old('email') }}">
                </div>

                <div class="form-group">
                    <label for="email">Senha</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Digite sua senha" value="">
                </div>

                <div class="form-group">
                    <input type="checkbox" name="remember"> Continuar Logado
                </div>

                <div class="form-group padding-rl-10 col-md-12 btn-center text-center">
                    <button style="width: 100%" type="submit" class="btn btn-success"><i class="fa fa-envelope"></i> &nbsp;Entre Com Seu E-mail</button>
                </div>

                <div class="form-group padding-rl-10 col-md-12 btn-center text-center">
                    <a style="width: 100%" class="btn btn-primary" href="{{$login_url}}" role="button"><i class="fa fa-facebook-square"></i> &nbsp;Entre Com Facebook</a>
                </div>

                <small>Ainda não é registrado? Clique aqui para <a href="{{asset('User/Cadastro')}}">Criar Conta</a>.</small>
            </div>

        </form>

    </div>

</div>

@include('Components.Partials.Layout.BottomPage')