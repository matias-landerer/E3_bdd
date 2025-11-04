--Creación de entidades.
CREATE TABLE Persona (
    ID SERIAL PRIMARY KEY,
    RUN CHAR(10) UNIQUE NOT NULL,
    Nombre VARCHAR(30) NOT NULL,
    Apellido VARCHAR(30) NOT NULL,
    Direccion VARCHAR(100),
    Correo VARCHAR(100) CHECK (Correo ~ '^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$'),
    Telefono CHAR(9) CHECK (Telefono ~ '^[1-9][0-9]{8}$'),
    rol VARCHAR(20) CHECK (rol IN ('Staff médico', 'administrativo', 'paciente')),
    profesion VARCHAR(30) CHECK (profesion IN ('TENS','enfermero/a','kinesiólogo/a','médico(a)')),
    especialidad VARCHAR(30),
    firma TEXT,
    tipo VARCHAR(15) CHECK (tipo IN ('titular','beneficiario')),
    titular CHAR(10) REFERENCES Persona(RUN),
    IDInstitucion INT REFERENCES InstitucionSalud(ID),
    CONSTRAINT chk_especialidad_medico CHECK (
        (profesion = 'médico(a)' AND especialidad IS NOT NULL)
        OR (profesion != 'médico(a)' AND especialidad IS NULL)
    )
);

CREATE TABLE InstitucionSalud (
    ID SERIAL PRIMARY KEY,
    codigo INT NOT NULL UNIQUE,
    nombre VARCHAR(30) NOT NULL,
    tipo VARCHAR(10) CHECK (Tipo IN ('abierta','cerrada')),
    rut CHAR(12) NOT NULL UNIQUE,
    Enlace TEXT
);

CREATE TABLE Plan (
    IDInstitucion INT PRIMARY KEY REFERENCES InstitucionSalud(ID),
    Bonificacion INT CHECK (Bonificacion BETWEEN 0 AND 100),
    Grupo VARCHAR(100)
);

CREATE TABLE Farmacia (
    ID SERIAL PRIMARY KEY,
    Cod INT UNIQUE NOT NULL,
    Nombre VARCHAR(100) NOT NULL,
    Descripcion TEXT NOT NULL,
    Tipo VARCHAR(30) CHECK (Tipo IN ('Alimentos','Equipamiento','Fármacos','Insumos','Psicotrópicos','Refrigerados','Sueros')),
    CodONU INT,
    ClasONU VARCHAR(30),
    Clasificacion VARCHAR(50) NOT NULL,
    Estado VARCHAR(10) CHECK (Estado IN ('Activo','Inactivo')),
    Esencial BOOLEAN,
    Precio INT CHECK (Precio >= 0)
);

CREATE TABLE ArancelFonasa (
    ID SERIAL PRIMARY KEY,
    CodF INT UNIQUE NOT NULL,
    CodA INT,
    Atencion VARCHAR(100),
    Valor INT CHECK (Valor >= 0),
    Grupo VARCHAR(30),
    Tipo VARCHAR(30)
);

CREATE TABLE ArancelDCColita (
    ID SERIAL PRIMARY KEY,
    Codigo INT UNIQUE NOT NULL,
    CodFonasa INT REFERENCES ArancelFonasa(CodF),
    Atencion VARCHAR(100),
    Valor INT CHECK (Valor >= 0)
);

CREATE TABLE Atencion (
    ID SERIAL PRIMARY KEY,
    RunPaciente CHAR(10) REFERENCES Persona(RUN),
    RunMedico CHAR(10) REFERENCES Persona(RUN),
    Diagnostico VARCHAR(100),
    Efectuada BOOLEAN DEFAULT FALSE
);

CREATE TABLE Medicamento (
    ID SERIAL PRIMARY KEY,
    fecha DATE,
    IDAtencion INT REFERENCES Atencion(ID),
    Nombre VARCHAR(100) REFERENCES Farmacia(Nombre),
    Posologia VARCHAR(100),
    Psicotropico BOOLEAN DEFAULT FALSE
);

CREATE TABLE Orden (
    ID SERIAL PRIMARY KEY,
    IDAtencion INT REFERENCES Atencion(ID),
    IDArancel INT REFERENCES ArancelDCColita(ID),
    Consulta VARCHAR(100)
);
