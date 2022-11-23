<article class="profile" data-id="{{ $user->id }}">
<header> 
<h1 href="/lei/{{ $user->id }}" style="margin-left: auto" style="margin-right: auto">{{ $user->name }}</h1>
  @if (Auth::user()->id == $user->id)
  <div class=container11>
  <div class=profile_image>
    <img src="https://www.promoview.com.br/uploads/images/unnamed%2819%29.png" alt="Foto de Perfil"> </br>
  </div>

  <div id="iprof">
    <li>Name: <?=$user->name ?>  </li>
    <li>Email: <?=$user->email?> </li>
    <li>Password: <?=$user->password?> </li>
    <li>Projects: <?=$user->project?>  </li> 
    <a href="/profile/{{ $user->id }}/edit" type="button" class="btn btn-primary" onClick="document.location.href='pages/edit_profile'" style="float:center;"> Edit Profile </a>

</div>

 @endif
</header>
</article>