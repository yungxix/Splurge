<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceTier;
use DOMDocument;
use DOMElement;
use DOMXPath;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $xml_path = storage_path('app/db/seed/services.xml');

        $dom = new DOMDocument();

        $dom->load(realpath($xml_path));

        $xpath = new DOMXPath($dom);

        $services = $xpath->query('/splurge-app/services/service');

        foreach ($services as $serviceEl) {
            DB::transaction(function () use ($serviceEl, $xpath) {
                static::importService($serviceEl, $xpath);
            });
            
        }


    }


    private static function importService(DOMElement $element, DOMXPath $xpath) {
        $service = new Service(array_merge([
            'display' => 'menu',
            'name' => $element->getAttribute('name'),
            'image_url' => $element->getAttribute('image-url'),
        ], static::getDescriptionArray($xpath->query('description', $element)->item(0), 'description')));

        $service->saveOrFail();

        $tierEls = $xpath->query('tiers/tier', $element);

        $position = 1;

        foreach ($tierEls as $tierEl) {
            $service->tiers()->save(static::buildServiceTier($tierEl, $position, $xpath));
            $position += 1;   
        }

       


    }


    private static function buildServiceTier(DOMElement $element, $position, DOMXPath $xpath): ServiceTier {
        $tier = new ServiceTier(array_merge([
            'name' => $element->getAttribute('name'),
            'position' => $position,
            
        ], static::getDescriptionArray($xpath->query('description', $element)->item(0), 'description')));

        $tier->code = $tier->generateCode();

        if ($element->hasAttribute('price')) {
            $tier->price = floatval($element->getAttribute('price'));
        }

        if ($element->hasAttribute('image-url')) {
            $tier->image_url = $element->getAttribute('image_url');
        }

        



        $options = [];

        $optionElements = $xpath->query('options/item', $element);

        foreach ($optionElements as $el) {
            $options[] = [
                'text' => trim($el->textContent)
            ];
        }

        $groupElements = $xpath->query('options/groups/group', $element);

        foreach ($groupElements as $group) {
            $description = $xpath->query('description', $group);

            $item = static::getDescriptionArray($description->item(0));

            $groupItems = $xpath->query('items/item', $group);

            $items = [];

            foreach ($groupItems as $el) {
                $items[] = ['text' => trim($el->textContent)];
            }

            $item['items'] = $items;


            $options[] = $item;
        }

        $tier->options = $options;
        return $tier;

    }

    private static function getDescriptionArray(DOMElement $element, $attribute = NULL): array {
        if (is_null($attribute) && $element->hasAttribute('html')) {
            return ['text' => '', 'html_text' => trim($element->textContent)];
        }
        if (!is_null($attribute)) {
            return [
                $attribute => trim($element->textContent)
            ];
        }
        return ['text' => trim($element->textContent)];
    }

}
