@php
  if(session()->has('profile_image'))
  {
    $src = session()->get('profile_image');
  }
  else {
      $record = backpack_user()->profile_image()->first();

      if(is_null($record))
      {
          $src = backpack_avatar_url(backpack_auth()->user());
      }
      else
      {
          $src = $record->value;
          session()->put('profile_image', $src);
      }
  }
@endphp
<li class="nav-item dropdown pr-4">
  <a href="#" class="nav-link" id="navToggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" onmouseenter="$('#navToggle').click()">
    <img class="img-avatar" src="{{ $src }}" alt="{{ backpack_auth()->user()->name }}">
  </a>
  <div class="dropdown-menu {{ config('backpack.base.html_direction') == 'rtl' ? 'dropdown-menu-left' : 'dropdown-menu-right' }} mr-4 pb-1 pt-1">
    <a class="dropdown-item" href="{{ route('backpack.account.info') }}"><i class="la la-user"></i> {{ trans('backpack::base.my_account') }}</a>
    <div class="dropdown-divider"></div>
    <a class="dropdown-item" href="{{ backpack_url('logout') }}"><i class="la la-lock"></i> {{ trans('backpack::base.logout') }}</a>
  </div>
</li>
