# ADR 001: Architecture Foundation & Evidence-First System

* **Status**: Accepted
* **Date**: 2026-10-08
* **Deciders**: Lead Architect & Project Owner
* **Milestone**: V0.1 — Research Foundation
* **Related Issue**: #1

---

## 1. Context

Indian cultural heritage digital platforms frequently suffer from three systemic shortcomings:
1. They aggregate unverified popular narratives, myth, and folklore without distinguishing them from empirical archaeological or epigraphic evidence.
2. They treat historical chronology as precise modern timestamps, flattening complex scholarly debates into arbitrary dates.
3. They lack traceable primary bibliographic provenance for their factual claims.

We require a software and data architecture that enforces historiographical discipline, transparency, and traceability from the ground up.

---

## 2. Decision

We decide to build **Bharat Heritage Atlas** on the following foundational tenets:

1. **Stack**:
   * Laravel 12 on PHP 8.4+
   * Normalized Relational Database (MySQL / SQLite for local development)
   * Livewire + Alpine.js + Tailwind CSS for a reactive, clean, research-grade user interface
   * RESTful APIs for programmatic and data consumption
2. **Evidence Model**:
   * Core entities (`heritage_sites`, `objects`, `inscriptions`) do not store unreferenced claims directly as flat text attributes.
   * Statements are stored as atomized `claims` linked via pivot relationships to `evidence` and verifiable `sources`.
   * Standardized evidence classifications: `STRONG_EVIDENCE`, `GOOD_EVIDENCE`, `SCHOLARLY_DEBATE`, `TRADITIONAL_ACCOUNT`, `UNVERIFIED`.
3. **Dual Dating Representation**:
   * A historiographical textual statement (e.g. *"2nd–1st century BCE"*) alongside machine-queryable astronomical year ranges (`start_year`, `end_year`, era enums, century indicators).
4. **Decoupled Research Pipeline**:
   * Research dossiers must be curated in `docs/research/<site-slug>/` with primary and peer-reviewed citations before ingestion into database migrations or seeders.
5. **Rigorous GitHub Workflow**:
   * All work is tracked via Milestones, Issues, Feature Branches, and Pull Requests with Conventional Commits.

---

## 3. Alternatives Considered

* **Alternative A (Generic CMS / WordPress)**: Rapid to stand up, but lacks the relational data integrity required for multi-source claim verification, historical date uncertainty modeling, and structured API endpoints.
* **Alternative B (Direct Graph / Document Database)**: Flexible schema, but lacks the strict relational constraints, automated foreign key cascading, and mature tooling provided by Laravel Eloquent.

---

## 4. Reason for Choice

Laravel 12 paired with PHP 8.4 provides an expressive, highly testable, robust relational ORM, battle-tested validation suites, and seamless modern frontend integration (Livewire). This ensures database-level integrity for evidence linkages while keeping maintenance straightforward.

---

## 5. Consequences

* **Positive**:
  * Unimpeachable academic credibility and transparency.
  * Every displayed statement is traceable to a cited source.
  * Easy future expansion to APIs, research datasets, and AI retrieval agents.
* **Negative / Trade-offs**:
  * Ingestion requires painstaking manual research and verification rather than rapid bulk data scraping.
  * Complex relational schema compared to simple blog/CMS models.
