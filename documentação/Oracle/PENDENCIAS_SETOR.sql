SELECT TO_CHAR(hm.DH_MEDICACAO, 'YYYY_MM_DD') AS ORD_PERIODO,
      TO_CHAR(hm.DH_MEDICACAO, 'DD/MM/YYYY') AS PERIODO,
      lt_set.CD_SETOR, lt_set.NM_SETOR,
      pm.CD_PRE_MED, pm.CD_ATENDIMENTO,
      pm.HR_PRE_MED, hm.DH_MEDICACAO,
      itpm.CD_ITPRE_MED,
      esq.CD_TIP_ESQ, esq.DS_TIP_ESQ,
      tf.DS_TIP_FRE
      FROM dbamv.PRE_MED pm
      INNER JOIN dbamv.ITPRE_MED itpm
        ON itpm.CD_PRE_MED = pm.CD_PRE_MED
      INNER JOIN dbamv.TIP_FRE tf
        ON tf.CD_TIP_FRE = itpm.CD_TIP_FRE
      INNER JOIN dbamv.TIP_ESQ esq
        ON esq.CD_TIP_ESQ = itpm.CD_TIP_ESQ
      INNER JOIN dbamv.PRESTADOR prest
        ON prest.CD_PRESTADOR = pm.CD_PRESTADOR
      INNER JOIN dbamv.HORARIO_MEDICACAO hm
        ON hm.CD_ITPRE_MED = itpm.CD_ITPRE_MED
      INNER JOIN dbamv.ATENDIME atd
        ON atd.CD_ATENDIMENTO = pm.CD_ATENDIMENTO
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
                  AND st.CD_SETOR = 51) lt_set
        ON lt_set.CD_ATENDIMENTO = atd.CD_ATENDIMENTO
        AND hm.DH_MEDICACAO BETWEEN lt_set.DT_ENTRADA AND lt_set.DT_SAIDA
      WHERE prest.CD_TIP_PRESTA IN (4,8)
      AND itpm.CD_TIP_ESQ IN ('GAS','ANT','CUR','DEP','DET','FOR','HEM','HID','MAR','MCD','MDP','MED','MNP','MOD','MUC','PE2','PRE','PRO','QT','SOR','SSR','SUP')
      AND pm.HR_PRE_MED >= SYSDATE -2
      AND hm.DH_MEDICACAO >= SYSDATE-1
