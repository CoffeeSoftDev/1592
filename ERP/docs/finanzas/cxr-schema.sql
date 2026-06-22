-- ============================================================
--  CXR — Cuentas Recuperadas
--  Schema: hgpqgijw_finanzas   |   MySQL 8.0.31   |   InnoDB
--  Charset/Collation: latin1_swedish_ci (alineado al esquema vivo)
--  Generado: 2026-06-21  (Fase F1)
-- ============================================================

USE `hgpqgijw_finanzas`;

-- 1) CATÁLOGO DE ESTADO
CREATE TABLE `cxr_estado` (
  `idEstado`  INT NOT NULL AUTO_INCREMENT,
  `Estado`    VARCHAR(30) NOT NULL,
  `Stado`     TINYINT NOT NULL DEFAULT 1,
  PRIMARY KEY (`idEstado`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `cxr_estado` (`idEstado`,`Estado`) VALUES
  (1,'Abierto'), (2,'Saldado'), (3,'Vencido');

-- 2) TRANSACCIÓN RAÍZ — cxr
CREATE TABLE `cxr` (
  `idCXR`            INT NOT NULL AUTO_INCREMENT,
  -- negocio
  `Cliente`          VARCHAR(150) NOT NULL,
  `Concepto`         VARCHAR(255) DEFAULT NULL,
  `encargado`        VARCHAR(100) DEFAULT NULL,
  -- montos
  `MontoInicial`     DOUBLE NOT NULL DEFAULT 0,
  `Saldo`            DOUBLE NOT NULL DEFAULT 0,
  -- fechas de negocio
  `FechaCreacion`    DATE NOT NULL,
  `FechaCompromiso`  DATE DEFAULT NULL,
  -- auditoría
  `created_at`       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  -- status
  `id_estado`        INT NOT NULL DEFAULT 1,
  -- referencias
  `id_UDN`           INT DEFAULT NULL,
  `id_Subcategoria`  INT DEFAULT NULL,
  `id_Folio_origen`  INT DEFAULT NULL,
  -- soft-delete
  `active`           TINYINT NOT NULL DEFAULT 1,
  PRIMARY KEY (`idCXR`),
  KEY `id_estado` (`id_estado`),
  KEY `id_UDN` (`id_UDN`),
  KEY `id_Subcategoria` (`id_Subcategoria`),
  KEY `id_Folio_origen` (`id_Folio_origen`),
  CONSTRAINT `cxr_ibfk_1` FOREIGN KEY (`id_estado`)
    REFERENCES `cxr_estado` (`idEstado`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `cxr_ibfk_2` FOREIGN KEY (`id_Subcategoria`)
    REFERENCES `subcategoria` (`idSubcategoria`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `cxr_ibfk_3` FOREIGN KEY (`id_Folio_origen`)
    REFERENCES `folio` (`idFolio`) ON DELETE SET NULL ON UPDATE CASCADE
  -- id_UDN sin FK: 'udn' reside en hgpqgijw_usuarios (otra BD) -> integridad por aplicación
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- 3) DETALLE — cxr_abono
CREATE TABLE `cxr_abono` (
  `idAbono`       INT NOT NULL AUTO_INCREMENT,
  -- negocio
  `Observacion`   VARCHAR(255) DEFAULT NULL,
  -- montos
  `MontoAbono`    DOUBLE NOT NULL DEFAULT 0,
  -- fecha de negocio
  `FechaAbono`    DATE NOT NULL,
  -- auditoría
  `created_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  -- FKs
  `id_CXR`        INT NOT NULL,
  `id_Folio`      INT DEFAULT NULL,
  `id_FormasPago` INT DEFAULT NULL,
  -- soft-delete
  `active`        TINYINT NOT NULL DEFAULT 1,
  PRIMARY KEY (`idAbono`),
  KEY `id_CXR` (`id_CXR`),
  KEY `id_Folio` (`id_Folio`),
  KEY `id_FormasPago` (`id_FormasPago`),
  CONSTRAINT `cxr_abono_ibfk_1` FOREIGN KEY (`id_CXR`)
    REFERENCES `cxr` (`idCXR`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `cxr_abono_ibfk_2` FOREIGN KEY (`id_Folio`)
    REFERENCES `folio` (`idFolio`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `cxr_abono_ibfk_3` FOREIGN KEY (`id_FormasPago`)
    REFERENCES `formas_pago` (`idFormas_Pago`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
