


<article class="edit_profile" data-id="{{ $user->id }}">
<header> 
<h1 href="/lei/{{ $user->id }}" style="margin-left: auto" style="margin-right: auto">{{ $user->name }}</h1>
  @if (Auth::user()->id == $user->id)
  <div class=container11>
  <div class=profile_image>
    <img src="https://www.promoview.com.br/uploads/images/unnamed%2819%29.png" alt="Foto de Perfil"> </br>
    <input class="btn btn-primary" style="margin-left: 5em;" type="button" value="Change Profile Picture">
  </div>

  <form id="iprof">
      <div>Name: <input type="text" class="form-control" name="name" placeholder="{{$user->name}}"></div><br>
      <div>Email:  <input type="email" class="form-control" name="email" placeholder="{{$user->email}}"></div><br>
      <div>Password:  <input type="password" class="form-control" name="password" placeholder="{{$user->password}}"></div><br>
      <div>Projects: </div>

      <button type="submit" class="btn btn-primary">
        edit
    </button>
</div>
</form>

 @endif
</header>
</article>
