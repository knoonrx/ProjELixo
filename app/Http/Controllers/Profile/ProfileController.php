<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Auth\RolesController;
use App\Http\Controllers\Services\EmailController;
use App\Models\RolesModel;
use App\Providers\ConstantesProvider;
use App\User;
use Exception;
use Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input;
use Redirect;

class ProfileController extends Controller
{

    // seleciona e exibe os detalhes do usuário
    static function index()
    {

       return function($UserID = null)
       {

           try
           {
               if( is_null($UserID) || $UserID == Auth::user()->id )
               {
                   $user = Auth::user();
               }
               else
               {
                   if($UserID != Auth::user()->id && is_null(RolesController::validar('Aluno')))
                   {
                       return redirect('/')->with('error', 'Você não têm permissão para acessar aquela página!');
                   } else {
                       try
                       {
                           $user = User::where('id', $UserID)->firstOrFail();
                       } catch (Exception $e)
                       {
                           return redirect('/')->with('error', 'Usário não foi encontrado');
                       }

                   }

               }
           }
           catch (Exception $e)
           {
               return redirect('/')->with('error', 'Ocorreu um erro');
           }

           return view('Profile.ProfileIndex', [
                'UserId'        => $user->id,
                'UserName'      => $user->name,
                'UserLastname'  => $user->lastname,
                'UserEmail'     => $user->email,
                'Facebook'      => $user->facebook_profile_link,
                'Picture'       => $user->picture,
                'UserRole'      => RolesModel::getName($user->user_roles),
           ]);
       };
    }

    // editar os detalhes do usuário
    static function edit()
    {

        return function($UserID = null)
        {

            try
            {
                if( is_null($UserID) || $UserID == Auth::user()->id )
                {
                    $user = Auth::user();
                }
                else
                {

                    if($UserID != Auth::user()->id && is_null(RolesController::validar('Admin')))
                    {
                        return redirect('/')->with('error', 'Você não têm permissão para acessar aquela página!');
                    } else {
                        try
                        {
                            $user = User::where('id', $UserID)->firstOrFail();
                        } catch (Exception $e)
                        {
                            return redirect('/')->with('error', 'Usário não foi encontrado');
                        }

                    }

                }
            }
            catch (Exception $e)
            {
                return redirect('/')->with('error', 'Ocorreu um erro');
            }

            return view('Profile.ProfileEdit', [
                'UserId'        => $user->id,
                'UserName'      => $user->name,
                'UserLastname'  => $user->lastname,
                'Enable'        => ($user->enable == 0) ? 'checked=checked' : '',
                'UserEmail'     => $user->email,
                'UserRole'      => RolesModel::getName($user->user_roles),
                'RolesLists'    => RolesModel::all()->sortBy('id'),
            ]);
        };
    }

    // modifica o userprofile
    static function update()
    {

        /**
         * @return $this
         */
        return function()
        {
            try
            {

                $input = Input::all();

                // faz a validaçao dos campos de usuario
                $validar = User::validar('edit');

                // se o id passado pelo form for diferente do usuario logado,
                // e a classe do user logado for diferente de admin return false
                if($input['id'] != Auth::user()->id && is_null(RolesController::validar('Admin')))
                    return Redirect::back()->withErrors('Voce nao tem permissão para isso.')->withInput();

                // se avalidaçao falhar retorne com erros
                if ($validar->fails())
                    return Redirect::back()->withErrors($validar)->withInput();

                // seleciona o usuario na base dados
                $user = User::find($input['id']);

                // se não encontrar o usuário retorna com erro
                if(is_null($user))
                    return Redirect::back()->withErrors('Usuário não foi encontrado.')->withInput();

                // se o email do usuário for diferente do email do imput
                if($user->email != $input['email'])
                {
                    // verfique se o email não pertença a outro usuário
                    if (User::hasemail($input['email']))
                        return Redirect::back()->withErrors('email já cadastrado.')->withInput();
                }
//                dd($input['enable'] . ' - ' . $user->user_roles);
                if (isset($input['enable']) and $input['enable'] == 0 and $user->user_roles > 1)
                    return Redirect::back()->withErrors('Você só pode desativar usuários, então primeiro mude a classe desse para usuário antes.')->withInput();

                $user->enable = (isset($input['enable'])) ? 0 : 1;
                $user->fill($input);
                $user->save(); // salva as mudanças no banco de dados

            } catch (Exception $e)
            {
                return Redirect::back()->withErrors('Algo saiu errado.'. $e->getMessage())->withInput();
            }
            return redirect('/Perfil/'.$input['id'])->with('status', 'Perfil Atualizado com sucesso');
        };

    }

//    trocar senha
    static function senha()
    {

        return function()
        {

            try
            {
                $input = Input::all();

                // faz a validaçao dos campos senha e confirmar senha
                $validar = User::validarsenha($input);
                if ($validar->fails())
                    return Redirect::back()->withErrors($validar)->withInput();

                // seleciona o usuario na base dados
                $user = User::find($input['id']);
                if($user)
                {
                    $user->password = Hash::make($input['password']);
                    $user->save();

                    try
                    {
                        $enviar           = new EmailController();//envia email
                        $enviar->assunto  = 'Sua senha foi modificada!';
                        $enviar->mensagem = 'Sua senha de acesso ao portal '. ConstantesProvider::SiteName.' acabou de ser modificada.';
                        $enviar->enviar($user->name,$user->lastname,$user->email,$enviar->assunto,$enviar->mensagem);
                    }catch (Exception $e){
                        return redirect('/Perfil/'.$input['id'])->with('status', 'Perfil Atualizado com sucesso, mas não foi possível enviar um email.');
                    }

                }

            } catch (Exception $e)
            {
                return Redirect::back()->withErrors('Algo saiu errado.')->withInput();
            }

            return redirect('/Perfil/'.$input['id'])->with('status', 'Perfil Atualizado com sucesso');

        };

    }

}
