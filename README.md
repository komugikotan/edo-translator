# 江戸弁翻訳API

## APIの使い方
### クエリ

このAPIが要求するクエリは、3つです。

・sentence  
・from (tokyoかedo)  
・to (tokyoかedo)  

### JSON

結果はJSON形式で返ってきます。例えば、「[http://komugio.starfree.jp/api/edo_translator/?sentence=このAPIを使ってみました%E3%80%82&from=tokyo&to=edo](http://komugio.starfree.jp/api/edo_translator/?sentence=このAPIを使ってみました%E3%80%82&from=tokyo&to=edo)」  のように送ると、結果は  「{"sentence":"\u3053\u306eAPI\u3092\u4f7f\u3063\u3066\u307f\u307e\u3057\u305f\u3002"}」のように返ってきます。

## サーバーにインストールして使う
### 使い方

PHP7以上で動きます。「dict」フォルダ内に翻訳時の辞書が保存されていて、基本的に保存されている単語を置換するだけです。ただ、名詞や動詞などは、変換後の単語と変換前の単語の**品詞が一致する場合のみ**変換します。

### CSVファイル

「dict」フォルダには３つのCSVファイル辞書があります。

一つが、「words.csv」で、このリストにある単語は、変換前の単語が名詞である場合のみ置換します。

二つ目が、「advanced.csv」で、このリストにある単語は、同CSVファイルに記述されている品詞と、置換する単語の品詞が一致する場合のみ置換します。

三つ目が、「verb.csv」で、このリストにある単語(文字列)は最後に単語を強制的に置換します。うまく置換されなかった時のための応急処置策です。

### Goo API
このプログラムは、品詞の判定にGooラボの形態素解析APIを利用しています。
[![Goo](https://u.xgoo.jp/img/sgoo.png)](http://www.goo.ne.jp/)
