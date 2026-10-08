# Research Dossier Guidelines — Bharat Heritage Atlas

## 1. Traceable Research Repository

Research in Bharat Heritage Atlas is strictly decoupled from application code. Before any data enters database migrations or seeders, it must be thoroughly researched and documented under this directory.

```text
docs/research/
├── README.md
├── gudimallam/
│   ├── research.md     # Historiographical context, excavation history, architectural phases
│   ├── claims.md       # Extracted historical claims with evidence linkage
│   └── sources.md      # Primary and academic bibliographic citations
├── heliodorus-pillar/
│   ├── research.md
│   ├── claims.md
│   └── sources.md
└── sanchi/
    ├── research.md
    ├── claims.md
    └── sources.md
```

---

## 2. Research Workflow

For every new heritage site or artifact:

1. **GitHub Research Issue**: Open a tracked GitHub issue under the `research` label and relevant milestone.
2. **Source Collection**: Gather primary archaeological excavation reports (ASI), epigraphic publications (*Epigraphia Indica*, CII), and peer-reviewed journal papers.
3. **Dossier Creation**: Establish `docs/research/<slug>/`.
4. **Source Registration (`sources.md`)**: Document author, title, publication date, publisher, journal/volume, page numbers, and permanent identifiers (DOI/URL).
5. **Claim Extraction (`claims.md`)**: Break down historical propositions into atomized statements.
6. **Evidence Assessment**: Link each claim to supporting/opposing evidence and assign one of the standardized classifications (`STRONG_EVIDENCE`, `GOOD_EVIDENCE`, `SCHOLARLY_DEBATE`, `TRADITIONAL_ACCOUNT`, `UNVERIFIED`).
7. **Uncertainty & Debates**: Explicitly record divergent scholarly interpretations.
8. **Structured Conversion**: Only once a research dossier is reviewed and verified does it transition to database seeders.

---

## 3. Initial Target Dataset (V0.1 Milestone)

1. **Gudimallam Parasurameshwara Linga** (Chittoor/Tirupati dist., Andhra Pradesh)
   * Earliest extant sculptured anthropomorphic linga, verified by I.K. Sarma's ASI excavations.
2. **Heliodorus Pillar & Besnagar Complex** (Vidisha, Madhya Pradesh)
   * 2nd century BCE Garuda-dhvaja pillar with Brahmi inscription recording Heliodorus, ambassador of Indo-Greek King Antialcidas.
3. **Sanchi Stupa Complex** (Raisen, Madhya Pradesh)
   * UNESCO World Heritage site with Mauryan, Shunga, and Satavahana inscriptions and monumental architecture.
