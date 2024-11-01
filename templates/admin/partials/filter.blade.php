<div id="filters">
  <form method="GET" action="">
    <label for="min_date">{!! __('Min date', 'elk-analytics') !!}</label>
    <input type="date" name="min_date" value="{{ $filter->min_date->format('Y-m-d') }}" min="{{ $filter->filter_min_date->format('Y-m-d') }}" max="{{ $filter->filter_max_date->format('Y-m-d') }}">
    
    <label for="max_date">{!! __('Max date', 'elk-analytics') !!}</label>
    <input type="date" name="max_date" value="{{ $filter->max_date->format('Y-m-d') }}" min="{{ $filter->filter_min_date->format('Y-m-d') }}" max="{{ $filter->filter_max_date->format('Y-m-d') }}">
    
    <input type="submit" value="{{ __('Filter', 'elk-analytics') }}" class="button button-primary">
    <input type="hidden" name="page" value="{{ $filter->page }}">
  </form>
</div>

