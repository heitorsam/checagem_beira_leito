CREATE USER checkbeiraleito IDENTIFIED BY tbpkrag#2021;

GRANT CREATE SESSION TO checkbeiraleito;
GRANT CREATE PROCEDURE TO checkbeiraleito;
GRANT CREATE TABLE TO checkbeiraleito;
GRANT CREATE VIEW TO checkbeiraleito;
GRANT UNLIMITED TABLESPACE TO checkbeiraleito;
GRANT CREATE SEQUENCE TO checkbeiraleito;


GRANT EXECUTE ON dbasgu.FNC_MV2000_HMVPEP TO checkbeiraleito;

GRANT SELECT ON dbasgu.USUARIOS TO checkbeiraleito;
GRANT SELECT ON dbasgu.PAPEL_USUARIOS TO checkbeiraleito;

GRANT SELECT ON dbamv.ATENDIME TO checkbeiraleito;
GRANT SELECT ON dbamv.PACIENTE TO checkbeiraleito;
GRANT SELECT ON dbamv.PRESTADOR TO checkbeiraleito;
GRANT SELECT ON dbamv.LEITO TO checkbeiraleito;
GRANT SELECT ON dbamv.UNID_INT TO checkbeiraleito;
GRANT SELECT ON dbamv.SETOR TO checkbeiraleito;
GRANT SELECT ON dbamv.TIP_PRESC TO checkbeiraleito;
GRANT SELECT ON dbamv.UNI_PRO TO checkbeiraleito;
GRANT SELECT ON dbamv.TIP_PRESC TO checkbeiraleito;
GRANT SELECT ON dbamv.TIP_ESQ TO checkbeiraleito;
GRANT SELECT ON dbamv.MOV_INT TO checkbeiraleito;
GRANT SELECT ON dbamv.HRITPRE_CONS TO checkbeiraleito;
GRANT SELECT ON dbamv.ITPRE_MED TO checkbeiraleito;
GRANT SELECT ON dbamv.PRE_MED TO checkbeiraleito;
GRANT SELECT ON dbamv.CONVENIO TO checkbeiraleito;
GRANT SELECT ON dbamv.TIP_FRE TO checkbeiraleito;
GRANT SELECT ON dbamv.TIP_PRESTA TO checkbeiraleito;
GRANT SELECT ON dbamv.TISS_GUIA TO checkbeiraleito;
GRANT SELECT ON dbamv.TISS_ITGUIA TO checkbeiraleito;
GRANT SELECT ON dbamv.CONVENIO TO checkbeiraleito;
GRANT SELECT ON dbamv.HORARIO_MEDICACAO TO checkbeiraleito;

GRANT INSERT ON dbamv.PRESTADOR_ASSINATURA TO checkbeiraleito;
GRANT UPDATE ON dbamv.PRESTADOR_ASSINATURA TO checkbeiraleito;
GRANT DELETE ON dbamv.PRESTADOR_ASSINATURA TO checkbeiraleito;



GRANT EXECUTE ON PKG_TISS_UTIL TO checkbeiraleito;
