
CREATE TABLE usuarios (
  cpf varchar(11) NOT NULL,
  senha char(32) NOT NULL,
  logado varchar(14) NOT NULL DEFAULT '00000000000000',
  bloqueado int(1) NOT NULL DEFAULT 0,
  tent int(1) NOT NULL DEFAULT 0,
  cnes varchar(8) NOT NULL DEFAULT '00000000',
  ine varchar(10) NOT NULL DEFAULT '0000000000',
  tema varchar(20) NOT NULL DEFAULT 'padrao',
  perfil varchar(20) NOT NULL DEFAULT 'indicadores',
  PRIMARY KEY(cpf)
);

INSERT INTO usuarios (cpf, senha, logado, bloqueado, tent, cnes, ine, tema, perfil) VALUES
('admin', '202cb962ac59075b964b07152d234b70', '27072021173948', 0, 0, '00000000', '0000000000', 'padrao', 'indicadores');