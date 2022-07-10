@props(['label' => '', 'vertical' => false])
<div @class(['mb-4','flex flex-row' => !$vertical])>
    <div @class(['text-gray-800 font-bold', 'w-44' => !$vertical])>
        {{$label}}
    </div>
    <div @class(['mt-2' => $vertical, 'ml-4' => !$vertical])>
        {{$slot}}
    </div>
</div>