@props(['name', 'label', 'value'])

@if(is_array($value))
    <div class="admin-content-fields">
        @foreach($value as $key => $childValue)
            @php
                $childLabel = is_int($key)
                    ? match (strtolower((string) $label)) {
                        'steps' => 'Journey step '.($loop->iteration),
                        'items' => str_contains($name, '[decisions]') ? 'Decision '.($loop->iteration) : (str_contains($name, '[documents]') ? 'Document '.($loop->iteration) : 'Item '.($loop->iteration)),
                        default => str()->headline((string) $label).' '.($loop->iteration),
                    }
                    : str_replace(['_', '-'], ' ', (string) $key);
                $childName = $name.'['.$key.']';
            @endphp
            <x-admin.content-fields :name="$childName" :label="$childLabel" :value="$childValue" />
        @endforeach
    </div>
@elseif(is_bool($value))
    <label class="auth-check">
        <input name="{{ $name }}" type="checkbox" value="1" @checked($value)>
        {{ str()->headline($label) }}
    </label>
@elseif(strtolower((string) $label) === 'source path')
    <input name="{{ $name }}" type="hidden" value="{{ $value }}">
@elseif(strtolower((string) $label) === 'icon')
    <label class="auth-field">
        <span>Feature icon</span>
        <select class="auth-input" name="{{ $name }}">
            <option value="clinic" @selected($value === 'clinic')>Clinic</option>
            <option value="specialist" @selected($value === 'specialist')>Specialist</option>
            <option value="journey" @selected($value === 'journey')>Journey</option>
            <option value="support" @selected($value === 'support')>Support</option>
        </select>
    </label>
@elseif(in_array(strtolower((string) $label), ['href', 'url', 'variant'], true))
    <label class="auth-field">
        <span>{{ str()->headline($label) }}</span>
        <input class="auth-input" name="{{ $name }}" value="{{ $value }}">
    </label>
@else
    @php
        $friendlyLabel = match (strtolower((string) $label)) {
            'kicker' => 'Small heading above the title',
            'headline' => 'Main page heading',
            'lead' => 'Introduction text',
            'heading' => 'Section heading',
            'number' => 'Step number',
            'icon' => 'Icon key (clinic, specialist, journey or support)',
            'title' => 'Card heading',
            'text' => 'Description text',
            'owner' => 'Responsible person',
            'decision' => 'Question or decision',
            'why' => 'Why this matters',
            'coordination' => 'How we coordinate this',
            default => str()->headline((string) $label),
        };
    @endphp
    <label class="auth-field">
        <span>{{ $friendlyLabel }}</span>
        <textarea class="auth-textarea compact js-rich-text" name="{{ $name }}">{{ $value }}</textarea>
    </label>
@endif
