sudo apt-get install sqlite3 libsqlite3-dev
sudo apt-get install php-sqlite3
sudo service apache2 restart

OR

Passo 1. A primeira coisa que devemos fazer é preparar o sistema operacional com os pacotes necessários para a compilação e construção dos pacotes a partir do código fonte. Ciente disso, abra um terminal (use as teclas CTRL + ALT + T);
Passo 2. Primeiramente, instale os softwares necessários à instalação;
sudo apt-get install build-essential tar wget
Passo 3. Em sequida baixe o código fonte do programa nesse endereço e salve-o como sqlite.tar.gz, ou use o comando abaixo;
wget -c https://www.sqlite.org/2021/sqlite-autoconf-3360000.tar.gz -O sqlite.tar.gz
Passo 4. Crie uma pasta para SQLite e acesse-a;
mkdir sqlite3 && cd sqlite3
Passo 5. Em seguida, descompacte o arquivo nesta pasta;
tar xvfz ../sqlite.tar.gz
Passo 6. Acesse a pasta gerada pela descompactação;
cd sqlite-autoconf-*/
Passo 7. E configure o código para iniciar a compilação;
./configure
Passo 8. Compile o SQLite com o comando make;
make
Passo 9. E, finalmente, instale-o;
sudo make install
Passo 10. No final, você pode verificar os resultados executando o comando sqlite3. Por exemplo, para mostrar a versão instalada;
sqlite3 --version