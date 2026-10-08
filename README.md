# Bharat Heritage Atlas

> **A serious, evidence-linked digital heritage platform for Indian cultural heritage.**

---

## 🏛️ Vision & Core Philosophy

Bharat Heritage Atlas is **NOT** a generic history website and **NOT** merely an AI chatbot. Its core intellectual and engineering asset is:

**HIGH-QUALITY, STRUCTURED, TRACEABLE, EVIDENCE-LINKED HERITAGE DATA.**

Every historical claim within this platform is explicitly linked to verified sources, categorized by evidence strength, and transparently marked with its scholarly consensus or uncertainty.

### Strict Historical Data Principles
* **Evidence-Linked**: No claim exists in isolation; each claim connects to primary archaeological, epigraphic, numismatic, or peer-reviewed literature.
* **Separation of Fact and Interpretation**: Clear distinction between established physical evidence, academic interpretations, traditional accounts, and unresolved debates.
* **Preservation of Chronological Nuance**: Historical dates are never flattened into arbitrary integers; date ranges, century estimates, BCE/CE designations, and scholarly variations are rigorously preserved.
* **Zero Fabrication Policy**: We never invent sources, citations, translations, dates, or scholarly consensus.

---

## 🔬 Research & Verification Workflow

```text
RESEARCH
   ↓
SOURCE COLLECTION
   ↓
CLAIM EXTRACTION
   ↓
EVIDENCE VERIFICATION
   ↓
STRUCTURED DATA
   ↓
DATABASE
   ↓
APPLICATION
   ↓
TESTING
   ↓
DOCUMENTATION
   ↓
GITHUB
```

---

## 🛠️ Technology Stack

* **Framework**: Laravel 12
* **Language**: PHP 8.4+
* **Database**: MySQL / SQLite (Development)
* **Frontend**: Blade, Livewire, Alpine.js, Tailwind CSS
* **API**: RESTful JSON endpoints
* **Version Control & PM**: GitHub (Issues, PRs, Milestones)

---

## 📁 Repository Structure

```text
bharat-heritage-atlas/
├── app/                  # Application code, Domain Models, Services
├── config/               # Application configuration
├── database/
│   ├── factories/        # Model factories for testing
│   ├── migrations/       # Normalized database migrations
│   └── seeders/          # Verified heritage data seeders
├── docs/                 # Primary documentation
│   ├── architecture/     # System architecture and technical design
│   ├── data-model/       # Conceptual and relational entity models
│   ├── decisions/        # Architectural Decision Records (ADRs)
│   ├── methodology/      # Evidence evaluation and classification rules
│   └── research/         # Traceable site-by-site research notes
├── resources/            # Views, stylesheets, JavaScript
├── routes/               # Web and API routing
└── tests/                # Feature and Unit test suites
```

---

## 🧭 Milestone Roadmap

* **V0.1 — Research Foundation**: Core data models, evidence framework, methodology documentation, and initial 3 verified heritage records (Gudimallam, Heliodorus Pillar, Sanchi).
* **V0.2 — Public Knowledge Platform**: Heritage site directory, detail explorer, evidence panels, confidence badges, source viewer, responsive interface.
* **V0.3 — Heritage Data Explorer**: Interactive geo-temporal mapping, dynasty & period filters, epigraphic search, artifact explorer.
* **V0.4 — AI Research Assistant**: Grounded retrieval agent operating exclusively on verified structured data and cited sources.
* **V0.5 — Institutional Services**: Digital archive services, research exports, and public API access.

---

## 🛡️ License & Integrity
Maintained under rigorous research and software engineering standards. Contributions must adhere to the [Evidence Methodology](docs/methodology/evidence-evaluation.md).
