--Alterações na estrutura do banco de dados devem ser feitas vias script


--08/05/2016

-- documentos
ALTER TABLE documentos
MODIFY observacoes varchar(1000);

ALTER TABLE documentos
add receituario varchar(1000);