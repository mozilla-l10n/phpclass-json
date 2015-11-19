<?php
namespace tests\units\Json;

use atoum;
use Json\Json as _Json;

require_once __DIR__ . '/../bootstrap.php';

class Json extends atoum\test
{
    public function testFetchContent()
    {
        $obj = new _Json;
        $json_content = $obj
            ->setURI(TEST_FILES . 'test_input.json')
            ->fetchContent();

        $this
            ->integer(count($json_content))
                ->isEqualTo(2);

        $this
            ->boolean(isset($json_content['www.mozilla.org']))
                ->isTrue();
    }

    public function testOutputContent()
    {
        $obj = new _Json;
        $json_data = [
            'first_element'  => 'test',
            'second_element' => [
                'a' => 'test a',
                'b' => 'test b',
            ],
        ];

        $json_content = file_get_contents(TEST_FILES . 'test_output_pretty.json');
        $json_output = $obj->outputContent($json_data, false, true);
        $this
            ->string($json_output)
                ->isEqualTo($json_content);

        $json_content = file_get_contents(TEST_FILES . 'test_output.json');
        $json_output = $obj->outputContent($json_data, false, false);
        $this
            ->string($json_output)
                ->isEqualTo($json_content);
    }

    public function testSaveFile()
    {
        $obj = new _Json;
        $json_data = [
            'key1' => 'value1',
            'key2' => 'value2',
        ];
        $tmp_filename = TEST_FILES . 'tmp.json';

        // Check standard output
        $obj->saveFile($json_data, $tmp_filename);
        $file_content = file_get_contents($tmp_filename);
        $this
            ->string($file_content)
                ->isEqualTo('{"key1":"value1","key2":"value2"}');

        // Check prettified output
        $obj->saveFile($json_data, $tmp_filename, true);
        $file_content = file_get_contents($tmp_filename);
        $this
            ->string($file_content)
                ->isEqualTo("{\n    \"key1\": \"value1\",\n    \"key2\": \"value2\"\n}");

        // Remove temp file
        unlink($tmp_filename);
    }
}
