<div class="table-container">
	<form action="{{ $table->getFormAction() }}" method="post">
		<div class="table-header">
			<div class="col-md-8">
				<h3>{{ $table->getTitle() }}</h3>
			</div>
			@if ($table->hasActions('table'))
				<div class="table-actions col-sm-4">
					@foreach($table->getActions('table') as $action)
						{!! $action->render() !!}
					@endforeach
				</div>
			@endif
		</div>
		<table class="table">
			<thead>
				<tr>
					@foreach ($table->getColumns() as $column)
						<th @foreach ($column->getAttributes() as $attr => $val)
							{{ $attr }}="{{ $val }}"
							@endforeach>
							@if ($column->isSortable() && $column->getHref())
								<a href="{{ $column->getHref() }}">
									{!! $column->getLabel() !!}
									@if (isset($column->sort))
										 <span class="glyphicon glyphicon-chevron-{{ $column->sort }}"></span>
									@endif
								</a>
							@else
								{{ $column->getLabel() }}
							@endif
						</th>
					@endforeach
				</tr>
			</thead>
			<tbody>
				@if($table->hasRows())
					@foreach ($table->getRows() as $row)
						<tr {!! $row->renderAttributes() !!} data-id="{{ $row->getId() }}">
							@foreach ($row->cells() as $cell)
								<td>{!! $cell->value !!}</td>
							@endforeach
                            @if ($row->hasActions())
                                <td>
                                    @foreach ($row->actions() as $action)
                                        {!! $action->render() !!}
                                    @endforeach
                                </td>
                            @endif
						</tr>
					@endforeach
				@else
					<tr>
						<td colspan="{{ count($table->getColumns()) }}">No Results Found.</td>
					</tr>
				@endif
			</tbody>
		</table>
	</form>
	<div class="table-footer">
		@if ($table->hasLinks())
			{!! $table->getLinks() !!}
		@endif
	</div>
</div>