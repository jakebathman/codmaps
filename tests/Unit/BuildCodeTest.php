<?php

use App\Support\BuildCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

dataset('buildCodes', [
    ['A01-151', 'A01', '15', '1'],
    ['A01-2711', 'A01', '271', '1'],
    ['A01-2F11', 'A01', '2F1', '1'],
    ['A01-5XGZH-JLU91-1', 'A01', '5XGZHJLU91', '1'],
    ['A01-5XGZN-U2F11-1', 'A01', '5XGZNU2F11', '1'],
    ['A01-6BQ11', 'A01', '6BQ1', '1'],
    ['A01-7AFKS-1', 'A01', '7AFKS', '1'],
    ['A01-8J11', 'A01', '8J1', '1'],
    ['A01-CTRG7-Y11', 'A01', 'CTRG7Y1', '1'],
    ['A01-G311', 'A01', 'G31', '1'],
    ['A01-H11', 'A01', 'H1', '1'],
    ['A01-Q11', 'A01', 'Q1', '1'],
    ['A01-Q51', 'A01', 'Q5', '1'],
    ['A01-Y11', 'A01', 'Y1', '1'],
    ['A01-Y51', 'A01', 'Y5', '1'],
    ['L04-21WSS-5WBJ4-TW11', 'L04', '21WSS5WBJ4TW1', '1'],
    ['L04-21Y1B-X7L85-4J11', 'L04', '21Y1BX7L854J1', '1'],
    ['L04-21Y1J-S4E1J-WD11', 'L04', '21Y1JS4E1JWD1', '1'],
    ['L04-28VHV-MN831-11', 'L04', '28VHVMN8311', '1'],
    ['L04-28VHV-MN831-31', 'L04', '28VHVMN8313', '1'],
    ['L04-2JD6U-HV77E-7111', 'L04', '2JD6UHV77E711', '1'],
    ['L04-2JD6Z-V2STK-CN11', 'L04', '2JD6ZV2STKCN1', '1'],
    ['L04-BQKCZ-7SD11', 'L04', 'BQKCZ7SD1', '1'],
    ['M01-21WSG-FBYK5-5I11', 'M01', '21WSGFBYK55I1', '1'],
    ['M01-21WSG-FHVFU-QZ11', 'M01', '21WSGFHVFUQZ1', '1'],
    ['M01-21XFM-29N1V-VK11', 'M01', '21XFM29N1VVK1', '1'],
    ['M01-21XFM-29SQR-9E11', 'M01', '21XFM29SQR9E1', '1'],
    ['M01-21XFM-29SQY-BE11', 'M01', '21XFM29SQYBE1', '1'],
    ['M01-21XFM-29SQY-CC11', 'M01', '21XFM29SQYCC1', '1'],
    ['M01-21XFM-2FPLG-UV11', 'M01', '21XFM2FPLGUV1', '1'],
    ['M01-N6M4R-2JTC1-1', 'M01', 'N6M4R2JTC1', '1'],
    ['P03-2JD73-8CA5C-TG11', 'P03', '2JD738CA5CTG1', '1'],
    ['P03-2JMFX-8I266-MT11', 'P03', '2JMFX8I266MT1', '1'],
    ['P03-2JN1M-4GJ39-5R11', 'P03', '2JN1M4GJ395R1', '1'],
    ['P03-2JN1M-4GJ39-6811', 'P03', '2JN1M4GJ39681', '1'],
    ['P03-AA942-ARE5V-11', 'P03', 'AA942ARE5V1', '1'],
    ['P03-AUXYZ-48S1Z-11', 'P03', 'AUXYZ48S1Z1', '1'],
    ['P03-AUXYZ-48VKG-11', 'P03', 'AUXYZ48VKG1', '1'],
    ['P03-QUXYZ-8XUGT-11', 'P03', 'QUXYZ8XUGT1', '1'],
    ['R01-VJYCK-KT471-1', 'R01', 'VJYCKKT471', '1'],

    // With base10 and attachment IDs
    ['A01-3LYXL-H9Y1-1', 'A01', '3LYXLH9Y1', '1', '4672924418048', ['4672924418048']],
    ['A01-KPVXY-7QIF1-1', 'A01', 'KPVXY7QIF1', '1', '1196268651381504', ['1196268651020288','348160','13056']],
]);

it('parses build codes into weapon, version, and attachments', function (
    string $code,
    string $weaponCode,
    string $attachmentsCode,
    string $versionCode,
    ?string $base10 = null,
    ?array $attachmentIds = null,
) {
    $buildCode = new BuildCode($code);

    expect($buildCode->weaponCode)->toBe($weaponCode)
        ->and($buildCode->versionCode)->toBe($versionCode)
        ->and($buildCode->attachmentsCode)->toBe($attachmentsCode);
    if ($base10 !== null) {
        expect($buildCode->base10)->toEqual($base10);
    }
    if ($attachmentIds !== null && $base10 !== null) {
        expect(count($buildCode->attachmentIds))->toBe(count($attachmentIds));
        // Loop over each attachment ID and check it exists in the build code's attachment IDs
        foreach ($attachmentIds as $attachmentId) {
            expect(in_array($attachmentId, $buildCode->attachmentIds))->toBeTrue();
        }
    }
})->with('buildCodes');
