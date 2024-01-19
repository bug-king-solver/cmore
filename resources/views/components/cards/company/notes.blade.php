<div class="grid grid-cols-1 gap-9 mt-6">
    @if($notes->isEmpty())
    {{ __('No notes found') }}
    @else
    @foreach ($notes as $note)
    <div class="flex rounded-md p-4 mt-5 md:mt-0 relative print:mt-20 border-l-2 border-l-[#1ECD51] bg-[#FAF9FB] flex-col justify-between !px-4 !py-2">
        <div class="text-esg29 font-encodesans flex h-auto text-lg font-bold">
            <span class="flex flex-row gap-3 justify-between w-full items-center text-[#0F1528]">
                {{ $note->title }}
            </span>
        </div>

        <div class="text-esg29 font-encodesans w-full h-auto">
            <div class="mb-3 mt-2 w-full h-full text-[#444444]">
                {!! nl2br($note->description) !!}
            </div>
        </div>

        <div class="w-full flex flex-col justify-center bottom-0">
            <div class="flex flex-row gap-2 pt-3 justify-end w-full">
                <x-buttons.edit modal="companies.modals.notes.form" :data="json_encode(['company' => $note->company->id, 'note' => $note->id])" class="cursor-pointer" :param="json_encode(['color' => color(16), 'width' => 14, 'height' => 14])" />
                <x-buttons.trash modal="companies.modals.notes.delete" :data="json_encode(['note' => $note->id])" class="px-2 py-1" class="cursor-pointer" :param="json_encode(['stroke' => color(16)])" />
            </div>
        </div>
    </div>
    @endforeach
    @endif
</div>