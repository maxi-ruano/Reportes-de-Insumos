<!-- Name Form Input -->
<div class="form-group @if ($errors->has('name')) has-error @endif">
    {!! Form::label('name', 'Name') !!}
    {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Name']) !!}
    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
</div>

<!-- email Form Input -->
<div class="form-group @if ($errors->has('email')) has-error @endif">
    {!! Form::label('email', 'Email') !!}
    {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Email']) !!}
    @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
</div>

<!-- password Form Input -->
<div class="form-group @if ($errors->has('password')) has-error @endif">
    {!! Form::label('password', 'Password') !!}
    {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password']) !!}
    @if ($errors->has('password')) <p class="help-block">{{ $errors->first('password') }}</p> @endif
</div>

<!-- sucursal Form Input -->
<div class="form-group @if ($errors->has('sucursal')) has-error @endif">
    {!! Form::label('sucursal', 'Sucursal') !!}
    {!! Form::select('sucursal', $sucursales, isset($user) ? $user->sucursal : null,  ['class' => 'form-control']) !!}
    @if ($errors->has('sucursal')) <p class="help-block">{{ $errors->first('sucursal') }}</p> @endif
</div>

<!-- Roles Form Input -->
<div class="form-group @if ($errors->has('roles')) has-error @endif">
    {!! Form::label('roles[]', 'Roles') !!}
    {!! Form::select('roles[]', $roles, isset($user) ? $user->roles->pluck('id')->toArray() : null,  ['class' => 'form-control', 'multiple']) !!}
    @if ($errors->has('roles')) <p class="help-block">{{ $errors->first('roles') }}</p> @endif
</div>

<!-- Permissions -->
@if(isset($user))
    @include('shared._permissions', ['closed' => 'true', 'model' => $user ])
@endif


<!-- Asociar al usuario de Licta  -->
<div class="form-group @if ($errors->has('sys_user_id')) has-error @endif">
    {!! Form::label('sys_user_id', 'Deseas asociar al usuario de LICTA') !!}
    {!! Form::select('sys_user_id', $sys_users, isset($user) ? $user->sys_user_id : null,  ['class' => 'form-control', 'placeholder' => 'Seleccione']) !!}
    @if ($errors->has('sys_user_id')) <p class="help-block">{{ $errors->first('sys_user_id') }}</p> @endif
</div>