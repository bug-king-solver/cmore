<svg xmlns="http://www.w3.org/2000/svg" width="{{ $width ?? '16' }}" height="{{ $height ?? '14' }}"
    class="{{ $class ?? '' }}" viewBox="0 0 24 24" fill="none">
    <path opacity="{{ $up ? '0.3' : 1 }}" d="M16 18L16 6M16 6L20 10.125M16 6L12 10.125" stroke="{{ $color ?? '#cccc' }}"
        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
    <path opacity="{{ !$up ? '0.3' : 1 }}" d="M8 6L8 18M8 18L12 13.875M8 18L4 13.875" stroke="{{ $color ?? '#cccc' }}"
        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
</svg>
