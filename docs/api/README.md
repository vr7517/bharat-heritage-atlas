# Bharat Heritage Atlas — REST API Reference (v1)

The Bharat Heritage Atlas REST API provides public, read-only programmatic access to verified Indian cultural heritage monuments, objects, inscriptions, and the multi-tiered Evidence Verification Engine.

---

## 1. Base URL & Routing Architecture

* **API Version**: `v1`
* **Local Development Base URL**: `http://localhost:8000/api/v1`
* **Production Base URL**: `https://api.bharatheritageatlas.in/api/v1`
* **OpenAPI 3.1 Contract**: [`docs/api/openapi.yaml`](./openapi.yaml)

All API responses are formatted using standard JSON envelopes:
```json
{
  "success": true,
  "data": { ... },
  "meta": { ... }
}
```

Standard error responses return appropriate HTTP status codes (e.g. `404 Not Found`):
```json
{
  "success": false,
  "message": "Heritage site with slug 'unknown' not found."
}
```

---

## 2. Endpoints Overview

| Method | Endpoint | Description |
|---|---|---|
| `GET` | `/sites` | Paginated listing of heritage sites with multi-criteria filters. |
| `GET` | `/sites/{slug}` | Detailed view of a heritage site with complete evidence graph and child objects. |
| `GET` | `/objects` | Paginated listing of physical heritage objects (sculptures, pillars, stupas). |
| `GET` | `/objects/{slug}` | Detailed view of a heritage object with linked claims and primary sources. |
| `GET` | `/inscriptions` | Epigraphical corpus listing filterable by script, language, and ruler. |
| `GET` | `/inscriptions/{slug}` | Inscription detail with verbatim Brahmi facsimile text and translation. |
| `GET` | `/sources` | Searchable bibliographic repository filterable by reliability tier. |
| `GET` | `/sources/{id}` | Bibliographic monograph details and linked evidentiary contributions. |
| `GET` | `/claims/{id}` | Atomized claim verification breakdown with supporting/conflicting evidence. |
| `GET` | `/evidence/{id}` | Archaeological/epigraphic evidence node with methodology and page citations. |
| `GET` | `/timeline` | Multi-entity chronological stream ordered by astronomical year. |
| `GET` | `/geo/sites` | RFC 7946 GeoJSON `FeatureCollection` for interactive digital mapping. |

---

## 3. Query Parameters & Filtering Guide

### 3.1 Dual Historical Dating & Astronomical Numbering
The API uses astronomical year numbering for filtering:
* Negative numbers represent years Before Common Era (**BCE**):
  * $300\text{ BCE} = -300$
  * $113\text{ BCE} = -113$
* Positive numbers represent Common Era (**CE**):
  * $930\text{ CE} = 930$
* Parameters:
  * `from_year`: Matches entities whose `start_year >= from_year`.
  * `to_year`: Matches entities whose `end_year <= to_year`.

### 3.2 Reliability Tiers for Bibliographic Sources
Filter `/sources?reliability_tier={TIER}`:
* `TIER_1_PRIMARY_EXCAVATION_EPIGRAPHY`: Official ASI excavation reports, in situ epigraphic corpora (*Corpus Inscriptionum Indicarum*, *Epigraphia Indica*).
* `TIER_2_PEER_REVIEWED_ACADEMIC`: University press monographs, peer-reviewed journal articles.
* `TIER_3_HISTORIOGRAPHICAL_SURVEY`: Secondary overviews and historical syntheses.

---

## 4. Example cURL Requests

### List Sites in Madhya Pradesh:
```bash
curl -X GET "http://localhost:8000/api/v1/sites?state=Madhya+Pradesh" \
     -H "Accept: application/json"
```

### Retrieve Heliodorus Pillar with Full Evidence Tree:
```bash
curl -X GET "http://localhost:8000/api/v1/objects/heliodorus-garuda-pillar" \
     -H "Accept: application/json"
```

### Retrieve GeoJSON FeatureCollection for Map Integration:
```bash
curl -X GET "http://localhost:8000/api/v1/geo/sites" \
     -H "Accept: application/json"
```

### Query Timeline Stream for the 2nd Century BCE:
```bash
curl -X GET "http://localhost:8000/api/v1/timeline?from_year=-200&to_year=-100" \
     -H "Accept: application/json"
```
