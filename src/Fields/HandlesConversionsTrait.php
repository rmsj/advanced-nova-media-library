<?php

namespace Ebess\AdvancedNovaMediaLibrary\Fields;

/**
 * @mixin Media
 */
trait HandlesConversionsTrait
{
    public function conversionOnIndexView(string $conversionOnIndexView): self
    {
        return $this->withMeta(compact('conversionOnIndexView'));
    }

    public function conversionOnDetailView(string $conversionOnDetailView): self
    {
        return $this->withMeta(compact('conversionOnDetailView'));
    }

    public function conversionOnForm(string $conversionOnForm): self
    {
        return $this->withMeta(compact('conversionOnForm'));
    }

    public function conversionOnPreview(string $conversionOnPreview): self
    {
        return $this->withMeta(compact('conversionOnPreview'));
    }

    public function getConversionUrls(\Spatie\MediaLibrary\MediaCollections\Models\Media $media): array
    {
        return [
            // original needed several purposes like cropping
            '__original__' => $this->imageContent($media->getFullUrl(), $media) ,
            'indexView' => $media->getFullUrl($this->meta['conversionOnIndexView'] ?? ''),
            'detailView' => $media->getFullUrl($this->meta['conversionOnDetailView'] ?? ''),
            'form' => $this->imageContent($media->getFullUrl($this->meta['conversionOnForm'] ?? ''), $media),
            'preview' => $media->getFullUrl($this->meta['conversionOnPreview'] ?? ''),
        ];
    }

    /**
     * Avoids CORS problem, handle crop with image content.
     *
     * @param $url
     * @param $media
     * @return string
     */
    public function imageContent($url, $media)
    {
        if (!config('nova-media-library.crop-with-image-content')) {
            return $url;
        }

        // Read image path, convert to base64 encoding
        $imageData = base64_encode(file_get_contents($url));

        // Format the image SRC:  data:{mime};base64,{data};
        return 'data: '.$media->mime_type.';base64,'.$imageData;
    }
}
