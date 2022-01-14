CREATE USER assinaturas IDENTIFIED BY tbpkrag#2021;

GRANT CREATE SESSION TO assinaturas;
GRANT CREATE PROCEDURE TO assinaturas;
GRANT CREATE TABLE TO assinaturas;
GRANT CREATE VIEW TO assinaturas;
GRANT UNLIMITED TABLESPACE TO assinaturas;
GRANT CREATE SEQUENCE TO assinaturas;


GRANT EXECUTE ON dbasgu.FNC_MV2000_HMVPEP TO assinaturas;

GRANT SELECT ON dbasgu.USUARIOS TO assinaturas;
GRANT SELECT ON dbasgu.PAPEL_USUARIOS TO assinaturas;


GRANT SELECT ON dbamv.ATENDIME TO assinaturas;
GRANT SELECT ON dbamv.PACIENTE TO assinaturas;
GRANT SELECT ON dbamv.PRESTADOR TO assinaturas;
GRANT SELECT ON dbamv.LEITO TO assinaturas;
GRANT SELECT ON dbamv.UNID_INT TO assinaturas;
GRANT SELECT ON dbamv.SETOR TO assinaturas;
GRANT SELECT ON dbamv.TIP_PRESC TO assinaturas;
GRANT SELECT ON dbamv.UNI_PRO TO assinaturas;
GRANT SELECT ON dbamv.TIP_PRESC TO assinaturas;
GRANT SELECT ON dbamv.TIP_ESQ TO assinaturas;
GRANT SELECT ON dbamv.MOV_INT TO assinaturas;
GRANT SELECT ON dbamv.HRITPRE_CONS TO assinaturas;
GRANT SELECT ON dbamv.ITPRE_MED TO assinaturas;
GRANT SELECT ON dbamv.PRE_MED TO assinaturas;
GRANT SELECT ON dbamv.CONVENIO TO assinaturas;
GRANT SELECT ON dbamv.TIP_FRE TO assinaturas;
GRANT SELECT ON dbamv.TIP_PRESTA TO assinaturas;
GRANT SELECT ON dbamv.TISS_GUIA TO assinaturas;
GRANT SELECT ON dbamv.TISS_ITGUIA TO assinaturas;
GRANT SELECT ON dbamv.CONVENIO TO assinaturas;

GRANT INSERT ON dbamv.PRESTADOR_ASSINATURA TO assinaturas;
GRANT UPDATE ON dbamv.PRESTADOR_ASSINATURA TO assinaturas;
GRANT DELETE ON dbamv.PRESTADOR_ASSINATURA TO assinaturas;

grant execute on PKG_TISS_UTIL to assinaturas;
