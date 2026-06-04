@echo off
curl -i http://localhost/vp2025/index.php > tmp_index.txt
curl -i http://localhost/vp2025/prijava.php > tmp_prijava.txt
curl -i http://localhost/vp2025/KnjigeLista.php > tmp_knjige.txt
curl -i -X POST http://localhost/vp2025/controller/knjigaSnimi.php -d "isbn=0000000000002&naziv=SmokeTest2&autor=Tester&oznakaZanra=RM" -L -s -D tmp_post_headers.txt > tmp_post_body.txt
curl -i http://localhost/vp2025/NabavkeLista.php > tmp_nabavke.txt
curl -i "http://localhost/vp2025/api/router.php?akcija=knjige" > tmp_rest.txt
type tmp_post_headers.txt
