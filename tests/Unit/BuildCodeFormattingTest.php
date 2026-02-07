<?php

use App\Models\Attachment;
use App\Models\Weapon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

dataset('buildCodes', [
    ['A01', '21', '211', 'A01-211'],
    ['A01', '271', '2711', 'A01-2711'],
    ['A01', '24N1', '24N11', 'A01-24N11'],
    ['A01', '22FQ1', '22FQ1-1', 'A01-22FQ1-1'],
    ['A01', '271LQ1', '271LQ-11', 'A01-271LQ-11'],
    ['A01', '24NKHL1', '24NKH-L11', 'A01-24NKH-L11'],
    ['A01', '22G9CLU1', '22G9C-LU11', 'A01-22G9C-LU11'],
    ['A01', '25XEU3VF1', '25XEU-3VF11', 'A01-25XEU-3VF11'],
    ['A01', '23MK1FPP71', '23MK1-FPP71-1', 'A01-23MK1-FPP71-1'],
    ['A01', '21HAWEUATN1', '21HAW-EUATN-11', 'A01-21HAW-EUATN-11'],
    ['A01', '243YD5L5UL91', '243YD-5L5UL-911', 'A01-243YD-5L5UL-911'],
    ['A01', '21WSGFBYJX2J1', '21WSG-FBYJX-2J11', 'A01-21WSG-FBYJX-2J11'],
    ['A01', '283855UWB9IT7R', '28385-5UWB9-IT7R1', 'A01-28385-5UWB9-IT7R1'],
    ['A01', null, '', ''],
]);

it('formats build codes', function (
    string $weaponPrefix,
    ?string $attachmentsCode,
    string $formattedAttachmentsCode,
    string $formattedWeaponCode,
) {
    $attachment = Attachment::factory()->create([
        'code_base34' => $attachmentsCode,
    ]);

    $weapon = Weapon::factory()->create([
        'code_prefix' => $weaponPrefix,
    ]);

    expect($attachment->formatCode())->toEqual($formattedAttachmentsCode);
    expect($attachment->formatCode($weapon))->toEqual($formattedWeaponCode);
})->with('buildCodes');
