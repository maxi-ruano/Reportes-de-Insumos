ALTER TABLE etl_pregunta ADD COLUMN id_pregunta_traduccion smallint;
COMMENT ON COLUMN etl_pregunta.id_pregunta_traduccion IS 'id para cruzar la pregunta con todas su traducciones';
