<!-- This example requires Tailwind CSS v2.0+ -->
<div class="flex flex-col">
    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
      <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
          <table class="min-w-full divide-y divide-gray-200">
            @if ($table->hasCaption())
                <caption>
                    {{ $table->getCaption() }}
                </caption>
            @endif
            <thead class="bg-gray-50">
              <tr>
                  @foreach ($table->getColumns() as $column)
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        @if ($column->isSorted()) 
                        <a class="block link" href="{{ $table->getSortUrl($column) }}">
                            <div class="flex flex-row">
                                <span class="flex-grow">
                                    {{ $column->renderHead($loop) }}            
                                </span>
                                @if ($table->isSorted($column)) 
                                <span class="font-bold">
                                    @if ($table->getSortOrder($column) == 'desc')
                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                      </svg>
                                      
                                    @else
                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5" />
                                      </svg>
                                      
                                    @endif
                                </span>
                                @endif
                            </div>
                        </a>
                        @else
                        {{ $column->renderHead($loop) }}
                        @endif
                        
                    </th>      
                  @endforeach
              </tr>
            </thead>
            @if ($table->isPaginated())
            <tfoot>
                <tr class="bg-gray-50">
                    <td colspan="{{ count($table->getColumns())}}">
                        <div class="flex flex-row items-center justify-end">
                            {{ $table->getData()->links() }}
                        </div>
                    </td>
                </tr>
            </tfoot>
            @endif
            <tbody class="bg-white divide-y divide-gray-200">
                @if ($table->isEmpty())
                    <tr class="align-middle">
                        <td colspan="{{ count($table->getColumns()) }}" class="text-center">
                            <em>
                                No record was found
                            </em>
                        </td>
                    </tr>
                @endif
                @foreach ($table->getData() as $model)
                    @php
                        $row_loop = $loop;
                    @endphp
                    <tr class="align-top">
                        @foreach ($table->getColumns() as $column)
                            @if ('th' === $column->getTag())
                                <th scope="row" class="px-6 py-4 {{ $column->getCellClass($row_loop->index, $loop->index) }}">
                                    {{ $column->render($model, $row_loop, $loop)  }}
                                </th>
                            @else
                                <td class="px-6 py-4 {{ $column->getCellClass($row_loop->index, $loop->index) }}">
                                    {{ $column->render($model, $row_loop, $loop)  }}
                                </td>
                            @endif
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  