<?php

header("Access-Control-Allow-Origin: *");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	// GET送信されたリクエストパラメータ
    $from = $_GET['from'];
    $to = $_GET['to'];
    $sentence = $_GET['sentence'];

    $result = getParse($sentence)['word_list'];
    $words_array = csv_to_array("dict/words.csv");
    $verbs_array = csv_to_array("dict/verb.csv");
    $advanced_array = csv_to_array("dict/advanced.csv");

    if($to == "tokyo"){
        to_tokyo($result, $words_array, $verbs_array, $advanced_array);
    }
    elseif($to == "edo"){
        to_edo($result, $words_array, $verbs_array, $advanced_array);
    }
} 
elseif($_SERVER['REQUEST_METHOD'] == 'POST') {
	// POST送信されたリクエストパラメータ
    // GET送信されたリクエストパラメータ
    $from = $_POST['from'];
    $to = $_POST['to'];
    $sentence = $_POST['sentence'];

    $result = getParse($sentence)['word_list'];
    $words_array = csv_to_array("dict/words.csv");
    $verbs_array = csv_to_array("dict/verb.csv");
    $advanced_array = csv_to_array("dict/advanced.csv");

    if($to == "tokyo"){
        to_tokyo($result, $words_array, $verbs_array, $advanced_array);
    }
    elseif($to == "edo"){
        to_edo($result, $words_array, $verbs_array, $advanced_array);
    }
}



function to_edo($result, $words_array, $verbs_array, $advanced_array){
    foreach ($result as $result_each){
        $result_sentence = "";
        foreach ($result_each as $index => $value){
            $word_each = $value[0];
            $part_each = $value[1];
            $read_each = $value[2];
            $index += 1;
            $did_1111 = 0;

            //敬語の「お」がついてる場合
            if ($word_each == "お" and $part_each == "冠名詞"){
                $word_each = "";
            }

            //単語の変換
            foreach ($words_array as $value_ss){
                if ($word_each == $value_ss[0]){
                    $echo_result_value = str_replace($value_ss[0], $value_ss[1], $word_each);
                    $result_sentence .= $echo_result_value;
                    $did_1111 = 1;
                }
                else{
                }
            }

            

            //品詞をりかいして、動詞の変換
            foreach ($advanced_array as $advanced_verb_value){
                if ($word_each == $advanced_verb_value[0] and $part_each == $advanced_verb_value[2]){
                    $echo_result_advanced_value = str_replace($advanced_verb_value[0], $advanced_verb_value[1], $word_each);
                    $result_sentence .= $echo_result_advanced_value;
                    $did_1111 = 1;
                }
                else{
                }
            }

            //何もなかった場合
            if ($did_1111 == 0){
                $result_sentence .= $word_each;
            }

            }

        //単純に動詞の変換
        foreach ($verbs_array as $value_sss){
            $result_sentence = str_replace($value_sss[0], $value_sss[1], $result_sentence);
        }

        //旧字体に変換
        $result_sentence = New_to_Old_Kanji($result_sentence);

        $return_array['sentence'] = $result_sentence;

        print json_encode($return_array);

        //$result_result_sentence = getParse($result_sentence)['word_list'][0];

        //foreach ($result_result_sentence as $value_ssss){
        //    echo $value_ssss[2];
        //}
    }
}

function to_tokyo(){
    $result = getParse($sentence)['word_list'];
    $words_array = csv_to_array("dict/words.csv");
    $verbs_array = csv_to_array("dict/verb.csv");
    $advanced_array = csv_to_array("dict/advanced.csv");

    foreach ($result as $result_each){
        $result_sentence = "";
        foreach ($result_each as $index => $value){
            $word_each = $value[0];
            $part_each = $value[1];
            $read_each = $value[2];
            $index += 1;
            $did_1111 = 0;

            //敬語の「お」がついてる場合
            if ($word_each == "お" and $part_each == "冠名詞"){
                $word_each = "";
            }

            //単語の変換
            foreach ($words_array as $value_ss){
                if ($word_each == $value_ss[0]){
                    $echo_result_value = str_replace($value_ss[0], $value_ss[1], $word_each);
                    $result_sentence .= $echo_result_value;
                    $did_1111 = 1;
                }
                else{
                }
            }

            

            //品詞をりかいして、動詞の変換
            foreach ($advanced_array as $advanced_verb_value){
                if ($word_each == $advanced_verb_value[0] and $part_each == $advanced_verb_value[2]){
                    $echo_result_advanced_value = str_replace($advanced_verb_value[0], $advanced_verb_value[1], $word_each);
                    $result_sentence .= $echo_result_advanced_value;
                    $did_1111 = 1;
                }
                else{
                }
            }

            //何もなかった場合
            if ($did_1111 == 0){
                $result_sentence .= $word_each;
            }

            }

        //単純に動詞の変換
        foreach ($verbs_array as $value_sss){
            $result_sentence = str_replace($value_sss[0], $value_sss[1], $result_sentence);
        }

        //旧字体に変換
        $result_sentence = New_to_Old_Kanji($result_sentence);

        $return_array['sentence'] = $result_sentence;

        print json_encode($return_array);

        //$result_result_sentence = getParse($result_sentence)['word_list'][0];

        //foreach ($result_result_sentence as $value_ssss){
        //    echo $value_ssss[2];
        //}
    }
}

function getParse($sentence) {
    $url = 'https://labs.goo.ne.jp/api/morph';
    $data = ['app_id' => 'Your API Key', 'sentence' => $sentence];

    $data = http_build_query($data, "", "&");    
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true); //Goo APIはPOSTじゃないと結果を返してくれない
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $output = curl_exec($curl);

    curl_close($curl);

    return json_decode($output, true);
}

function csv_to_array($file_name){
    $csv = file_get_contents($file_name);
    $array_csv = explode("\n", $csv);

    foreach($array_csv as $key => $value){
        $array_output_csv[$key] = explode(",", $value);

    }

    return $array_output_csv;
}


function New_to_Old_Kanji($sentence)
{
    //旧字体と新字体のリスト
    $old_new_kanji = [
        [
            'old' => '變',
            'new' => '変',
        ],
    ];

    //全部変換
    foreach ($old_new_kanji as $value) {
        $sentence = str_replace($value['new'], $value['old'], $sentence);
    }

    return $sentence;
}
?>


