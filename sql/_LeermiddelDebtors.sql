SELECT
    CAST(SUBSTRING(ci.debcode, 12, 2) AS int) AS schoolid, 
    ci.debcode AS Relation, 
    DebtorNumber AS OffsetNumber, 
    ci.cmp_name AS OffSetName, 
    ci.cmp_fadd3 AS StudentName,
    cc.ClassificationID AS Classification, 
    (
        CASE ci.cmp_type 
            WHEN 'A' THEN 'Zakenrelatie'
            WHEN 'B' THEN 'Bank'
            WHEN 'C' THEN 'Klant'
            WHEN 'D' THEN 'Divisie'
            WHEN 'E' THEN 'Werknemer'
            WHEN 'N' THEN 'Niet gevalideerd'
            WHEN 'P' THEN 'Prospect'
            WHEN 'R' THEN 'Wederverkoper'
            WHEN 'L' THEN 'Lead'
            WHEN 'S' THEN 'Leverancier'
            WHEN 'T' THEN 'Suspect'
            ELSE ci.cmp_type 
        END
        ) AS RTYPE, 
        (
            CASE ci.cmp_status 
                WHEN 'A' THEN 'Actief'
                WHEN 'B' THEN 'Geblokkeerd'
                WHEN 'E' THEN 'Non-actief'
                WHEN 'N' THEN 'Niet gevalideerd'
                WHEN 'P' THEN 'Pilot'
                WHEN 'R' THEN 'Referentie'
                WHEN 'S' THEN 'Passief'
                ELSE ci.cmp_status
            END
        ) AS RSTATUS, 
        SUM
            (
                ROUND
                    (
                        (
                            CASE 
                                WHEN bt.AmountDC > 0 AND bt.Type = 'W' THEN bt.AmountDC 
                                ELSE 
                                    (
                                        CASE 
                                            WHEN bt.Type = 'S' AND bt.AmountDC < 0 THEN - bt.AmountDC 
                                            ELSE NULL
                                        END
                                    ) 
                            END
                        ), 
                        2
                    )
            ) AS Debit,
        SUM
            (
                ROUND
                    (
                        (
                            CASE 
                                WHEN bt.AmountDC < 0 AND bt.Type = 'W' THEN - bt.AmountDC 
                                ELSE 
                                    (
                                        CASE 
                                            WHEN bt.Type = 'S' AND bt.AmountDC > 0 THEN bt.AmountDC 
                                            ELSE NULL
                                        END
                                    ) 
                            END
                        ), 
                        2
                    )
            ) AS Credit,
        SUM
            (
                ROUND
                    (
                        (
                            CASE 
                                WHEN DATEDIFF(dd, ISNULL(bt.InvoiceDate, bt.ProcessingDate), getdate()) BETWEEN 0 AND 30 AND bt.Type = 'W' THEN bt.AmountDC 
                                ELSE 
                                    (
                                        CASE 
                                            WHEN DATEDIFF(dd, bt.ValueDate, getdate()) BETWEEN 15 AND 30 AND bt.Type = 'S' THEN - bt.AmountDC 
                                            ELSE NULL
                                        END
                                    ) 
                            END
                        ), 
                        2
                    )
            ) AS T1, 
        SUM
            (
                ROUND
                    (
                        (
                            CASE 
                                WHEN DATEDIFF(dd, ISNULL(bt.InvoiceDate, bt.ProcessingDate), getdate()) BETWEEN 31 AND 60 AND bt.Type = 'W' THEN bt.AmountDC 
                                ELSE 
                                    (
                                        CASE 
                                            WHEN DATEDIFF(dd, bt.ValueDate, getdate()) BETWEEN 31 AND 60 AND bt.Type = 'S' THEN - bt.AmountDC 
                                            ELSE NULL
                                        END
                                    ) 
                            END
                        ), 
                        2
                    )
            ) AS T2,
            SUM
                (
                    ROUND
                        (
                            (
                                CASE
                                    WHEN DATEDIFF(dd, ISNULL(bt.InvoiceDate, bt.ProcessingDate), getdate()) BETWEEN 61 AND 90 AND bt.Type = 'W' THEN bt.AmountDC 
                                    ELSE 
                                        (
                                            CASE 
                                                WHEN DATEDIFF(dd, bt.ValueDate, getdate()) BETWEEN 61 AND 90 AND bt.Type = 'S' THEN - bt.AmountDC 
                                                ELSE NULL
                                            END
                                        ) 
                                END
                            ),
                            2
                        )
                ) AS T3, 
            SUM
                (
                    ROUND
                        (
                            (
                                CASE 
                                    WHEN DATEDIFF(dd, ISNULL(bt.InvoiceDate, bt.ProcessingDate), getdate()) > 90 AND bt.Type = 'W' THEN bt.AmountDC 
                                    ELSE 
                                        (
                                            CASE 
                                                WHEN DATEDIFF(dd, bt.ValueDate, getdate()) > 90 AND bt.Type = 'S' THEN - bt.AmountDC 
                                                ELSE NULL
                                            END
                                        ) 
                                END
                            ), 
                            2
                        )
                ) AS T4,
            SUM
                (
                    ROUND
                        (
                            (
                                CASE 
                                    WHEN bt.Type = 'W' THEN bt.AmountDC 
                                    ELSE - bt.AmountDC 
                                END
                            ),
                            2
                        )
                ) AS AmountDC, 
            COUNT(*) AS TERM, 
            AVG(DATEDIFF(dd, ISNULL(ISNULL(bt.InvoiceDate, bt.ProcessingDate), bt.ValueDate), getdate())) AS iAge, 
            MAX(addr.AddressLine1) AS Adres, 
            MAX(addr.Postcode) AS Postcode, 
            MAX(addr.City) AS City,
			ci.cmp_e_mail as Email,
			ci.cmp_tel as GSM,
            MAX(addr.Phone) AS Tel, 
            MAX(addr.Fax) AS Fax, 
            MAX(cp.cnt_l_name) AS Contact, 
            MAX(ci.vatnumber) AS VatNumber,
            ci.cmp_fctry AS Country, 
            MAX(ci.creditline) AS Creditline
FROM
    (
        (
            SELECT
                '' AS Empty, 
                bt.ID, 
                DebtorNumber, 
                CreditorNumber, 
                ValueDate, 
                AmountDC, 
                AmountTC, 
                ProcessingDate, 
                InvoiceDate, 
                ReportingDate, 
                Type, 
                OffSetName, 
                PaymentType, 
                SupplierInvoiceNumber,
                CAST(Description AS VARCHAR(400)) AS Description, 
                TransactionType, 
                OffsetReference, 
                OffSetLedgerAccountNumber, 
                bt.Blocked, 
                DocumentID, 
                OrderNumber, 
                InvoiceNumber, 
                DueDate, 
                TcCode, 
                bt.Status, 
                bt.MatchID, 
                BatchNumber, 
                OwnBankAccount,
                EntryNumber, 
                bt.LedgerAccount, 
                bt.sysguid, 
                (
                    CASE 
                        WHEN ExchangeRate = 0 THEN ROUND(ExchangeRate, 6) 
                        ELSE ROUND(1 / ExchangeRate, 6) 
                    END
                ) AS ExchangeRate, 
                bt.Approved, 
                bt.Approved2
            FROM
                [400].[dbo].BankTransactions bt 
            LEFT OUTER JOIN
                (
                    SELECT
                        HS.ID, 
                        HS.StatementType
                    FROM
                        [400].[dbo].BankTransactions HS
                    WHERE
                        HS.Type = 'S'
                    AND
                        HS.Status = 'J'
                    AND
                        HS.DebtorNumber IS NOT NULL
                    AND
                        (
                            (
                                ISNULL(HS.StatementType, '') <> 'F'
                                AND
                                ISNULL(ISNULL(HS.InvoiceDate, HS.ProcessingDate), HS.valuedate) <= getdate()
                            ) 
                            OR
                                HS.StatementType = 'F'
                        )
                ) AS HS 
            ON bt.MatchID = HS.ID 
            LEFT OUTER JOIN
                (
                    SELECT
                        MatchID
                    FROM
                        [400].[dbo].BankTransactions W
                    WHERE
                        W.Type = 'W'
                    AND W.Status <> 'V'
                    AND W.MatchID IS NOT NULL
                    AND W.DebtorNumber IS NOT NULL
                    AND ISNULL(ISNULL(W.InvoiceDate, W.ProcessingDate), W.ValueDate) > getdate()
                    GROUP BY MatchID
                ) AS HW 
            ON bt.MatchID = HW.MatchID 
            AND HS.StatementType = 'F'
            INNER JOIN
                cicmpy ci 
            ON bt.DebtorNumber = ci.debnr
            WHERE
                Type = 'W'
            AND
                bt.Status IN ('C', 'A', 'P', 'J') 
            AND
                EntryNumber IS NOT NULL
            AND
                DebtorNumber IS NOT NULL
            AND
                ROUND(AmountDC, 2) <> 0
            AND
                ISNULL(ISNULL(InvoiceDate, ProcessingDate), ValueDate) <= getdate() 
            AND
                (
                    HS.ID IS NULL
                    OR
                        (
                            HS.ID IS NOT NULL
                            AND
                            HW.MatchID IS NOT NULL
                        )
                ) 
            AND
                (
                    ci.cmp_type = 'C'
                )
            UNION ALL
                SELECT
                    '' AS Empty,
                    s.ID, 
                    s.DebtorNumber, 
                    s.CreditorNumber, 
                    s.ValueDate, 
                    (ISNULL(s.AmountDC, 0) - ISNULL(W2.AmountDC, 0)) AS AmountDC, 
                    (ISNULL(s.AmountTC, 0) - ISNULL(W2.AmountTC, 0)) AS AmountTC,
                    s.ProcessingDate, 
                    s.InvoiceDate, 
                    s.ReportingDate, 
                    s.Type, 
                    s.OffSetName, 
                    s.PaymentType, 
                    s.SupplierInvoiceNumber, 
                    CAST(s.Description AS VARCHAR(400)) AS Description, 
                    s.TransactionType, 
                    s.OffsetReference,
                    s.OffSetLedgerAccountNumber, 
                    s.Blocked, 
                    s.DocumentID, 
                    s.OrderNumber, 
                    ISNULL(s.InvoiceNumber, '') AS InvoiceNumber, 
                    s.DueDate, 
                    s.TCCode, 
                    s.Status, 
                    s.MatchID, 
                    s.BatchNumber, 
                    s.OwnBankAccount,
                    s.EntryNumber,
                    s.LedgerAccount,
                    s.sysguid, 
                    (
                        CASE 
                            WHEN ExchangeRate = 0 THEN ROUND(ExchangeRate, 6) 
                            ELSE ROUND(1 / ExchangeRate, 6) 
                        END
                    ) AS ExchangeRate,
                    s.Approved,
                    s.Approved2
                FROM
                    [400].[dbo].BankTransactions s 
                INNER JOIN
                    cicmpy ci 
                ON
                    s.DebtorNumber = ci.debnr 
                LEFT OUTER JOIN
                    (
                        SELECT
                            MatchID, 
                            ROUND(SUM(ROUND(ISNULL(AmountDC, 0), 2)), 2) AS AmountDC, 
                            ROUND(SUM(ROUND(ISNULL(AmountTC, 0), 2)), 2) AS AmountTC
                        FROM
                            [400].[dbo].BankTransactions w
                        WHERE
                            w.Type = 'W'
                        AND
                            w.Status IN ('C', 'A', 'P', 'J') 
                        AND
                            ISNULL(w.InvoiceDate, ISNULL(w.ProcessingDate, w.ValueDate)) <= getdate() 
                        AND
                            w.EntryNumber IS NOT NULL
                        GROUP BY
                            MatchID
                        HAVING
                            MatchID IS NOT NULL
                    ) AS W2 
                ON W2.MatchID = S.ID
                WHERE
                    s.Type = 'S'
                AND
                    s.Status = 'J'
                AND
                    s.DebtorNumber IS NOT NULL
                AND
                    ROUND(s.AmountDC, 2) <> 0
                AND
                    ROUND((ISNULL(s.AmountDC, 0) - ISNULL(W2.AmountDC, 0)), 2) <> 0
                AND
                    (ci.cmp_type = 'C') 
                AND
                    s.ValueDate <= getdate()
        )
    ) bt 
INNER JOIN
    cicmpy ci 
ON
    bt.DebtorNumber = ci.debnr 
AND
    bt.DebtorNumber IS NOT NULL
LEFT OUTER JOIN
    addresses addr 
ON
    ci.cmp_wwn = addr.account 
AND
    addr.Type = 'INV'
LEFT OUTER JOIN
    classifications cc 
ON
    ci.ClassificationId = cc.ClassificationID 
LEFT OUTER JOIN
    cicntp cp 
ON
    cp.cnt_id = ci.cnt_id
WHERE
    ci.debcode IS NOT NULL
AND
    (ci.cmp_type = 'C') 
AND
    (
        (bt.Type = 'S' AND bt.ProcessingDate IS NULL) 
        OR
            CAST(FLOOR(CAST(bt.ProcessingDate AS FLOAT)) AS DATETIME) BETWEEN { d '1800-01-01' }
        AND
            getdate()
    ) 
AND
    ISNULL(ISNULL(bt.InvoiceDate, bt.ProcessingDate), bt.Valuedate) <= getdate() 
AND
    (
        bt.CreditorNumber IS NULL
        OR
            bt.CreditorNumber NOT IN ('000002')
    ) 
AND
    ci.cmp_status = 'A'
GROUP BY
    ci.debcode, 
    DebtorNumber, 
    ci.cmp_name,
    ci.cmp_fadd3, 
    ci.cmp_type, 
    ci.cmp_status, 
    ci.cmp_fctry,
	ci.cmp_e_mail,
	ci.cmp_tel, 
    cc.ClassificationID