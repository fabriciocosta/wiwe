<?Php
/*													*/
/*													*/
/*		Genera la tabla dentro de la base			*/
/*													*/
/*													*/

echo "nada";

/*INTERBASE

CREATE TABLE GENERICA (
    ID INTEGER NOT NULL,
    CAMPO1  INTEGER,
    CAMPO2  VARCHAR(50),
    CAMPO3  DECIMAL,
    CAMPO4  TIMESTAMP,
    CAMPO5  BLOB SUB_TYPE TEXT,
	CAMPO6	BLOB SUB_TYPE 0,
    PRIMARY KEY (ID)
);

CREATE TABLE LOOKUP (
    ID INTEGER NOT NULL,
    PAIS VARCHAR(25),
    PRIMARY KEY(ID)
);


set term !!;
create trigger "GENERICA_ASIGNAID" for "GENERICA"
active before insert position 100 as
begin
  if (new.ID is NULL) then
    new.ID = gen_id(GEN_GENERICA, 1);
end!!
set term ;!!
commit
*/
?>