# Architecture Overview — Bharat Heritage Atlas

## 1. System Intent

Bharat Heritage Atlas is engineered as an evidence-first digital catalog and exploration system. The architecture guarantees that no presentation of heritage data can detach from its evidentiary basis.

```
       +---------------------------------------------+
       |             Presentation Layer              |
       |  (Blade, Livewire, Alpine.js, Tailwind CSS) |
       +---------------------------------------------+
                              |
       +---------------------------------------------+
       |               Application Layer             |
       |  (Controllers, Services, Search, API)       |
       +---------------------------------------------+
                              |
       +---------------------------------------------+
       |             Domain & Evidence Engine        |
       |  (Heritage Sites, Objects, Inscriptions,    |
       |   Claims, Evidence Records, Sources)        |
       +---------------------------------------------+
                              |
       +---------------------------------------------+
       |              Relational Database            |
       |       (Normalized MySQL / SQLite schema)    |
       +---------------------------------------------+
                              ▲
                              |
       +---------------------------------------------+
       |           Research & Verification           |
       |    (docs/research/ Markdown Source Dossiers)|
       +---------------------------------------------+
```

## 2. Core Architectural Pillars

1. **Evidence-Linked Domain Model**: Every core entity (Site, Artifact, Inscription) associates with one or more `Claims`. Each `Claim` is supported or contradicted by one or more `Evidence` records, which in turn cite verified `Sources`.
2. **Dual-Representation Chronology**: Historical dates possess dual fields:
   * **Machine Chronology**: Numeric ranges (`start_year_bce_ce`, `end_year_bce_ce`, `confidence_interval`) for timeline indexing and temporal querying.
   * **Historiographical Statement**: Textual representations preserving nuanced scholarly phrasing (e.g., *"circa late 2nd century BCE to early 1st century BCE based on paleographic analysis"*).
3. **Decoupled Research Ingestion**: Scholarly investigation takes place in version-controlled markdown dossiers (`docs/research/<site>/`) containing full citations, claim breakdown, and peer review prior to database insertion.
4. **Non-destructive Verification**: Disputed claims are not reconciled into a single forced consensus; contradictory evidence records co-exist, highlighting scholarly discourse.

## 3. Technology Alignment

* **Runtime**: PHP 8.4+
* **Framework**: Laravel 12
* **Storage**: Relational database leveraging foreign keys and strict constraints
* **Frontend**: Server-rendered reactive UI (Livewire & Alpine.js) with Tailwind CSS styling
* **Testing**: PHPUnit / Pest testing verifying model relationships, evidence cascade, and duplicate prevention
