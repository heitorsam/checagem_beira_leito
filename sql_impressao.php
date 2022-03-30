<?php 

    $dt_valida = filter_input(INPUT_GET,'dt_inicio');

    $consulta_registros = "SELECT DISTINCT TO_CHAR(hm.DH_MEDICACAO,'DD/MM/YYYY') || '' AS DATA_PESQ, atd.CD_ATENDIMENTO, atd.CD_PACIENTE,
                           pac.NM_PACIENTE, TO_CHAR(pac.DT_NASCIMENTO,'DD/MM/YYYY') || ' ' AS DT_NASCIMENTO, 
                           pac.NM_MAE, conv.NM_CONVENIO, COUNT(hm.DH_MEDICACAO) AS QTD_PENDENCIA
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
                           INNER JOIN dbamv.PACIENTE pac
                               ON pac.CD_PACIENTE = atd.CD_PACIENTE   
                           INNER JOIN dbamv.CONVENIO conv
                               ON conv.CD_CONVENIO = atd.CD_CONVENIO    
                           WHERE prest.CD_TIP_PRESTA IN (4,8,9)
                           AND itpm.CD_TIP_ESQ IN ('GAS','ANT','CUR','DEP','DET','FOR','HEM','HID','MAR'  ,'MCD','MDP','MED','MNP','MOD','MUC','PE2','PRE','PRO','QT','SOR','SSR','SUP')
                           --AND pm.HR_PRE_MED >= SYSDATE -2
                           AND TO_CHAR(hm.DH_MEDICACAO,'YYYY-MM-DD') = '$dt_valida'
                           AND atd.CD_CONVENIO IN (29,156,8,73,45,28,61,62,10,154,16,60,31)
                           GROUP BY TO_CHAR(hm.DH_MEDICACAO,'DD/MM/YYYY') || '', atd.CD_ATENDIMENTO, atd.CD_PACIENTE,
                           pac.NM_PACIENTE, TO_CHAR(pac.DT_NASCIMENTO,'DD/MM/YYYY') || ' ', pac.NM_MAE, conv.NM_CONVENIO
                           ORDER BY conv.NM_CONVENIO ASC, atd.CD_ATENDIMENTO ASC";

    $result_registros = oci_parse($conn_ora, $consulta_registros);

    oci_execute($result_registros);

    //$row_reg = oci_fetch_array($result_registros);   
    
?>