{{-- This file is used for menu items by any Backpack v6 theme --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<x-backpack::menu-item title="Products" icon="las la-graduation-cap" :link="backpack_url('product')" />
<x-backpack::menu-item title="Product Types" icon="las la-graduation-cap" :link="backpack_url('product_type')" />
<x-backpack::menu-item title="Customers" icon="las la-graduation-cap" :link="backpack_url('customer')" />
