<?php

    include "cabecalho.php";

    ////////////
    //PACIENTE//
    ////////////

    echo $consulta_pac = "SELECT
    atd.DT_ALTA, pm.DT_PRE_MED, hm.DH_MEDICACAO, 
    pm.DT_PRE_MED AS PM_ONE, hm.DH_MEDICACAO AS MED_ONE,
    lt_set.CD_SETOR, lt_set.NM_SETOR,
    TO_CHAR(TO_DATE(TO_CHAR(NVL(atd.DT_ALTA,TO_DATE('01/01/1999','DD/MM/YYYY')),'DD/MM/YYYY'),'DD/MM/YYYY'),'YYYY_MM-DD') AS CONV_ONE,
    TO_CHAR(TO_DATE(TO_CHAR(pm.DT_PRE_MED,'DD/MM/YYYY'),'DD/MM/YYYY'),'YYYY_MM_DD') AS CONV_TWO,
    atd.CD_ATENDIMENTO, lt_set.DS_LEITO, atd.CD_PACIENTE, pac.NM_PACIENTE, pac.DT_NASCIMENTO, pac.NM_MAE
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
    AND TO_CHAR(TO_DATE(TO_CHAR(NVL(atd.DT_ALTA,TO_DATE('01/01/1999','DD/MM/YYYY')),'DD/MM/YYYY'),'DD/MM/YYYY'),'YYYY_MM-DD') 
        <> TO_CHAR(TO_DATE(TO_CHAR(pm.DT_PRE_MED,'DD/MM/YYYY'),'DD/MM/YYYY'),'YYYY_MM_DD')
    AND TO_CHAR(TO_DATE(TO_CHAR(NVL(atd.DT_ALTA,TO_DATE('01/01/1999','DD/MM/YYYY')),'DD/MM/YYYY'),'DD/MM/YYYY'),'YYYY_MM-DD') 
        <> TO_CHAR(TO_DATE(TO_CHAR(pm.DT_PRE_MED+1,'DD/MM/YYYY'),'DD/MM/YYYY'),'YYYY_MM_DD')
    AND TO_CHAR(TO_DATE(TO_CHAR(NVL(atd.DT_ALTA,TO_DATE('01/01/1999','DD/MM/YYYY')),'DD/MM/YYYY'),'DD/MM/YYYY'),'YYYY_MM-DD')
        <> TO_CHAR(TO_DATE(TO_CHAR(hm.DH_MEDICACAO,'DD/MM/YYYY'),'DD/MM/YYYY'),'YYYY_MM_DD')
    AND TO_CHAR(TO_DATE(TO_CHAR(NVL(atd.DT_ALTA,TO_DATE('01/01/1999','DD/MM/YYYY')),'DD/MM/YYYY'),'DD/MM/YYYY'),'YYYY_MM-DD')
        <> TO_CHAR(TO_DATE(TO_CHAR(hm.DH_MEDICACAO+1,'DD/MM/YYYY'),'DD/MM/YYYY'),'YYYY_MM_DD')
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
    AND mi.TP_MOV <> 'R'
    AND st.CD_SETOR = '51'
    ) lt_set
    ON lt_set.CD_ATENDIMENTO = atd.CD_ATENDIMENTO
    AND hm.DH_MEDICACAO BETWEEN lt_set.DT_ENTRADA AND lt_set.DT_SAIDA
    INNER JOIN dbamv.PACIENTE pac
    ON pac.CD_PACIENTE = atd.CD_PACIENTE
    WHERE prest.CD_TIP_PRESTA IN (4,8,9)
    AND itpm.CD_TIP_ESQ IN ('ANT','DPM','DEP','MAR','MCD','MED','MNP','MUC','QT','SOR','LM','HEM','LAB')
    AND itpm.CD_TIP_PRESC <> 47493
    AND pm.HR_PRE_MED >= SYSDATE -1
    AND hm.DH_MEDICACAO >= SYSDATE-1
    AND itpm.DH_CANCELADO IS NULL
    AND hm.CD_ITPRE_MED  || TO_CHAR(hm.DH_MEDICACAO,'DD/MM/YYYY HH24:MI:SS')
                      NOT IN (SELECT cons.CD_ITPRE_MED || TO_CHAR(cons.DH_MEDICACAO,'DD/MM/YYYY HH24:MI:SS')
                              FROM dbamv.HRITPRE_CONS cons
                              WHERE cons.SN_SUSPENSO = 'S'
                              AND TRUNC(cons.DH_CHECAGEM) >= TRUNC(SYSDATE-1))";

    $result_pac  = oci_parse($conn_ora, $consulta_pac);

    oci_execute($result_pac); 

    while($row_pac = oci_fetch_array($result_pac)){

        echo '<br>'. $row_pac['CD_ATENDIMENTO'] . ' - ' . $row_pac['DT_ALTA'] . ' - ' . $row_pac['DT_PRE_MED'] . ' - PM ONE: ' . $row_pac['PM_ONE'] . ' ' . ' - MED ONE: ' . $row_pac['MED_ONE'];
        echo '<br>'. $row_pac['CONV_ONE'] . ' - ' . $row_pac['CONV_ONE'];
    }

    include "rodape.php";

?>