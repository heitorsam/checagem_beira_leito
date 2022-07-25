<?php

    //CONEXAO
    include 'conexao.php';

    $var_pc = $_REQUEST['var_cd_paciente'];
    $var_st = $_REQUEST['var_cd_setor'];

    //Consulta no banco para trazer informações dos pacientes

    $consulta_paci = "SELECT TO_CHAR(hm.DH_MEDICACAO, 'YYYY_MM_DD') AS ORD_PERIODO,
                      TO_CHAR(hm.DH_MEDICACAO, 'DD/MM/YYYY') AS PERIODO,
                      lt_set.CD_SETOR, lt_set.NM_SETOR,
                      pm.CD_PRE_MED, pm.CD_ATENDIMENTO,
                      pm.HR_PRE_MED, TO_CHAR(hm.DH_MEDICACAO,'DD/MM/YYYY HH24:MI') AS DH_MEDICACAO,
                      itpm.CD_ITPRE_MED,
                      esq.CD_TIP_ESQ, esq.DS_TIP_ESQ,
                      tf.DS_TIP_FRE, tp.DS_TIP_PRESC
                      FROM dbamv.PRE_MED pm
                      INNER JOIN dbamv.ITPRE_MED itpm
                      ON itpm.CD_PRE_MED = pm.CD_PRE_MED
                      INNER JOIN dbamv.TIP_FRE tf
                      ON tf.CD_TIP_FRE = itpm.CD_TIP_FRE
                      INNER JOIN dbamv.TIP_ESQ esq
                      ON esq.CD_TIP_ESQ = itpm.CD_TIP_ESQ
                      INNER JOIN dbamv.PRESTADOR prest
                      ON prest.CD_PRESTADOR = pm.CD_PRESTADOR
                      INNER JOIN dbamv.HRITPRE_MED hm
                      ON hm.CD_ITPRE_MED = itpm.CD_ITPRE_MED
                      INNER JOIN dbamv.ATENDIME atd
                          ON atd.CD_ATENDIMENTO = pm.CD_ATENDIMENTO
                          AND NVL(TO_CHAR(atd.DT_ALTA,'DD/MM/YYYY'),'999999999999') <> TO_CHAR(pm.DT_PRE_MED,'DD/MM/YYYY')
                          AND NVL(TO_CHAR(atd.DT_ALTA,'DD/MM/YYYY'),'999999999999') <> TO_CHAR(pm.DT_PRE_MED+1,'DD/MM/YYYY')
                          AND NVL(TO_CHAR(atd.DT_ALTA,'DD/MM/YYYY'),'999999999999') <> TO_CHAR(hm.DH_MEDICACAO,'DD/MM/YYYY')
                          AND NVL(TO_CHAR(atd.DT_ALTA,'DD/MM/YYYY'),'999999999999') <> TO_CHAR(hm.DH_MEDICACAO+1,'DD/MM/YYYY')
                      LEFT JOIN dbamv.TIP_PRESC tp
                      ON tp.CD_TIP_PRESC = itpm.CD_TIP_PRESC
                      INNER JOIN (SELECT mi.CD_ATENDIMENTO, mi.CD_LEITO,
                                  mi.CD_LEITO_ANTERIOR, lt.DS_LEITO,
                                  st.CD_SETOR, st.NM_SETOR,
                                  mi.HR_MOV_INT AS DT_ENTRADA,
                                  NVL((SELECT MIN(HR_MOV_INT) -1/(24*60*60)
                                      FROM MOV_INT
                                      WHERE CD_ATENDIMENTO = mi.CD_ATENDIMENTO
                                      AND CD_LEITO_ANTERIOR = mi.CD_LEITO
                                      AND HR_MOV_INT >= mi.HR_MOV_INT), SYSDATE) AS DT_SAIDA
                                  FROM MOV_INT mi
                                  INNER JOIN dbamv.LEITO lt
                                  ON lt.CD_LEITO = mi.CD_LEITO
                                  INNER JOIN dbamv.UNID_INT unid
                                  ON unid.CD_UNID_INT = lt.CD_UNID_INT
                                  INNER JOIN dbamv.SETOR st
                                  ON st.CD_SETOR = unid.CD_SETOR
                                  WHERE mi.CD_ATENDIMENTO IS NOT NULL
                                  AND mi.SN_RESERVA IS NULL
                                  AND mi.HR_MOV_INT >= SYSDATE - 2
                                  AND st.CD_SETOR = $var_st) lt_set
                      ON lt_set.CD_ATENDIMENTO = atd.CD_ATENDIMENTO
                      AND hm.DH_MEDICACAO BETWEEN lt_set.DT_ENTRADA AND lt_set.DT_SAIDA
                      WHERE prest.CD_TIP_PRESTA IN (4,8,9)
                      AND itpm.CD_TIP_ESQ IN ('GAS','ANT','CUR','DEP','DET','FOR','HEM','HID','MAR','MCD','MDP','MED','MNP','MOD','MUC','PE2','PRE','PRO','QT','SOR','SSR','SUP')
                      AND pm.HR_PRE_MED >= SYSDATE -2
                      AND hm.DH_MEDICACAO >= SYSDATE-1
                      AND itpm.CD_ITPRE_MED || '-' || TO_CHAR(hm.DH_MEDICACAO,'DD/MM/YYYY HH24:MI:SS')
                          NOT IN (SELECT hcaux.CD_ITPRE_MED || '-' || TO_CHAR(hcaux.DH_MEDICACAO,'DD/MM/YYYY HH24:MI:SS')
                                  FROM dbamv.HRITPRE_CONS hcaux
                                  WHERE hcaux.DH_MEDICACAO >= SYSDATE-1)
                                  --AND hcaux.SN_SUSPENSO <> 'S')
                      AND itpm.CD_ITPRE_MED || '-' || TO_CHAR(hm.DH_MEDICACAO,'DD/MM/YYYY HH24:MI:SS')
                          NOT IN (SELECT csmdaux.CD_ITPRE_MED || '-' || TO_CHAR(csmdaux.DH_MEDICACAO,'DD/MM/YYYY HH24:MI:SS')
                                      FROM dbamv.HORA_COMPONT_IT_PRESCRIC_CSMD csmdaux
                                      WHERE csmdaux.DH_MEDICACAO >= SYSDATE-1)
                      AND atd.CD_PACIENTE = ". $var_pc . "
                      AND itpm.DH_CANCELADO IS NULL
                      ORDER BY hm.DH_MEDICACAO ASC";

    $result_paci  = oci_parse($conn_ora, $consulta_paci);

    @oci_execute($result_paci);    

    while($row_paci = oci_fetch_array($result_paci)){       

        $info_user[] = array(
            'res_prescricao' => $row_paci['CD_ITPRE_MED'],
            'res_esquema' => $row_paci['DS_TIP_ESQ'],
            'res_descricao' => $row_paci['DS_TIP_PRESC'],
            'res_dh_medicacao' => $row_paci['DH_MEDICACAO']
        );
    }

echo(json_encode($info_user));

?>