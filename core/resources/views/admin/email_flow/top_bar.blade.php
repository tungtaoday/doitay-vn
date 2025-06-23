<ul class="nav nav-tabs mb-4 topTap breadcrumb-nav" role="tablist">
    <button class="breadcrumb-nav-close"><i class="las la-times"></i></button>
    <li class="nav-item {{ menuActive('admin.email.flow.auto') }}" role="presentation">
        <a href="{{ route('admin.email.flow.auto') }}" class="nav-link text-dark" type="button">
            <i class="las la-robot"></i> @lang('Auto Flow')
        </a>
    </li>
    <li class="nav-item {{ menuActive('admin.email.flow.marketing') }}" role="presentation">
        <a href="{{ route('admin.email.flow.marketing') }}" class="nav-link text-dark" type="button">
            <i class="las la-bullhorn"></i> @lang('Marketing Flow')
        </a>
    </li>
    <li class="nav-item {{ menuActive('admin.email.flow.statistics') }}" role="presentation">
        <a href="{{ route('admin.email.flow.statistics') }}" class="nav-link text-dark" type="button">
            <i class="las la-chart-bar"></i> @lang('Statistics')
        </a>
    </li>
    <li class="nav-item {{ menuActive(['admin.setting.notification.templates','admin.setting.notification.template.edit']) }}" role="presentation">
        <a href="{{ route('admin.setting.notification.templates') }}" class="nav-link text-dark" type="button">
            <i class="las la-list"></i> @lang('All Templates')
        </a>
    </li>
    <li class="nav-item {{ menuActive('admin.setting.notification.email') }}" role="presentation">
        <a href="{{ route('admin.setting.notification.email') }}" class="nav-link text-dark" type="button">
            <i class="las la-cog"></i> @lang('Email Settings')
        </a>
    </li>
</ul> 