<?xml version="1.0" encoding="UTF-8"?>
<data type="string" success="1" status="200">
    <id>{{ $album->album_id }}</id>
    <title>{{ $album->title }}</title>
    <description>{{ $album->description }}</description>
    <datetime>{{ $album->created_at->timestamp }}</datetime>
    <account>{{ $album->account->account_id }}</account>
    <link>{{ $album->link }}</link>
    <images_count>{{ $album->images_count }}</images_count>
    @if ($album->images_count)
        <images>
            @foreach ($album->images as $image)
                <item>
                    <id>{{ $image->image_id }}</id>
                    <title>{{ $image->title }}</title>
                    @if ($image->description)
                        <description>{{ $image->description }}</description>
                    @else
                        <description/>
                    @endif
                    <datetime>{{ $image->created_at->timestamp }}</datetime>
                    @if ($image->covers)
                        @php
                            $covers = json_decode($image->covers);
                        @endphp
                        <covers>
                            @foreach ($covers as $cover)
                                <cover>{{ $cover }}</cover>
                            @endforeach
                        </covers>
                    @else
                        <covers/>
                    @endif
                    <width>{{ $image->width }}</width>
                    <height>{{ $image->height }}</height>
                    <size>{{ $image->size }}</size>
                    <views>{{ $image->views }}</views>
                    <link>{{ $image->link }}</link>
                </item>
            @endforeach
        </images>
    @else
        <images/>
    @endif
</data>