# ADR 002: Relational Data Model, Evidence Engine & Historical Dating Architecture

* **Status**: Proposed (Under Review)
* **Date**: 2026-10-08
* **Deciders**: Lead Architect & Project Owner
* **Milestone**: V0.1 — Research Foundation
* **Related Issue**: #5

---

## 1. Context

Bharat Heritage Atlas requires a normalized relational database schema to represent complex Indian cultural heritage entities alongside an unimpeachable evidence engine. 

Traditional historical platforms commonly introduce three fatal architectural simplifications:
1. **Flattened citations**: Associating a bibliography list with a whole site or article rather than linking specific factual claims to exact citations.
2. **Flattened chronology**: Forcing ancient dates into single numeric columns (e.g. `year = -100`), which discards nuanced scholarly dating phrasing (e.g., *"c. 2nd–1st century BCE based on Middle Brahmi paleography and Sungoid sculptural style"*).
3. **Consensus flattening**: Forcing disputed historical narratives into a single consensus field, erasing legitimate historiographical debates.

We must design a data model that preserves granularity, multi-vocal scholarship, and verifiable evidence at the database constraint level.

---

## 2. Decision

We establish the following database architecture:

### 2.1 Core Domain Entities
* **`locations`**: Normalized modern administrative and coordinates table with historical toponyms and geographical uncertainty radius.
* **`periods`** & **`dynasties`**: Standardized chronological epochs and royal houses.
* **`heritage_sites`**, **`objects`**, **`inscriptions`**: First-class physical and epigraphic entities with dedicated foreign keys to locations, periods, and dynasties.

### 2.2 Epistemic Evidence Engine
* **`claims`**: Granular, atomized historical assertions attached polymorphically (`claimable_type`, `claimable_id`) to Sites, Objects, or Inscriptions.
* **`evidence`**: Material or analytical findings (classified as `STRONG_EVIDENCE`, `GOOD_EVIDENCE`, `SCHOLARLY_DEBATE`, `TRADITIONAL_ACCOUNT`, `UNVERIFIED`).
* **`claim_evidence` (Pivot)**: Connects claims to evidence with a relationship qualifier (`SUPPORTS`, `CONTRADICTS`, `COMPLICATES`, `REINTERPRETS`). This allows contradictory evidence and competing hypotheses to co-exist without data loss.
* **`sources`**: Bibliographic catalogue with rigorous metadata (authors, publisher, year, DOI, pages, archival location, reliability tier).
* **`evidence_source` (Pivot)**: Links specific evidence points to sources with verbatim excerpts, exact plate/page references, and citation context.

### 2.3 Dual Historical Dating Model
* Every chronological entity stores:
  1. A human-readable historiographical statement (`dating_statement` string) preserving the scholar's exact qualitative nuance.
  2. Machine-queryable astronomical signed year ranges (`start_year`, `end_year` signed integers, where $-113$ equals $113\text{ BCE}$).
  3. An `is_dating_uncertain` boolean indicating debate or wide error margins.

---

## 3. Alternatives Considered

### Option A: Flat Text / JSON Citation Columns
* **Approach**: Store sources and citations as JSON columns on `heritage_sites` or `objects`.
* **Disadvantages**: Prevents relational integrity, prevents reusable sources across multiple monuments, makes citation search impossible, and prohibits SQL-level duplicate prevention.
* **Verdict**: Rejected.

### Option B: Monolithic 1-to-1 Claim-Source Direct Link
* **Approach**: Every claim points directly to a single source.
* **Disadvantages**: A single claim in archaeology is frequently corroborated by multiple distinct evidence streams (e.g. epigraphy AND excavation stratigraphy) documented across multiple publications.
* **Verdict**: Rejected.

### Option C: Decoupled Evidence Engine with Polymorphic Claim Binding (Chosen)
* **Approach**: Atomized claims linked through many-to-many pivots to evidence records and sources.
* **Advantages**: High reusability, supports scholarly dispute natively (`CONTRADICTS` relationship), completely traceable.
* **Verdict**: **Accepted**.

---

## 4. Consequences

### Positive
* Complete academic rigor: Any user or researcher can inspect *why* a date or identification is asserted and *which* sources dispute it.
* High API flexibility: Enables future timeline filtering, map geospatial clustering, and citation graph generation.
* Zero data distortion: Debates between renowned scholars (e.g. Coomaraswamy vs I.K. Sarma on Gudimallam dating) are natively represented.

### Trade-offs & Mitigations
* **Complexity**: Multiple joins required to assemble a full heritage dossier.
  * *Mitigation*: Leverage Eloquent eager loading (`with(['claims.evidence.sources'])`) and caching layers where appropriate.
* **Data Ingestion Overhead**: Data cannot be scraped casually; it must be curated into atomized claims.
  * *Mitigation*: Supported by the decoupled research workflow in `docs/research/`.
