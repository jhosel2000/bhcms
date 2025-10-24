# EHR Refactoring Tasks

## 1. Optimize DoctorEhrController index method
- [x] Fix N+1 queries in pending records query
- [x] Optimize patient query with better eager loading
- [x] Improve sorting performance (remove leftJoin for last_updated)
- [x] Add query result caching for better performance
- [x] Optimize status filtering logic

## 2. Add analytics dashboard
- [x] Create EhrService methods for EHR statistics (total records, pending reviews, approval rates)
- [x] Add analytics widgets to doctor/ehr/index.blade.php
- [x] Display monthly trends and record type distribution
- [x] Add quick action buttons for common tasks

## 3. Enhance EhrService
- [x] Add methods for creating prescriptions from EHR context
- [x] Add methods for creating referrals from EHR context
- [x] Improve PDF generation with better styling and data inclusion
- [x] Add comprehensive logging for all EHR operations
- [x] Add bulk operations support

## 4. Improve error handling and logging
- [x] Add try-catch blocks in DoctorEhrController methods
- [x] Add logging for all EHR actions (create, update, approve, flag)
- [x] Improve validation error messages
- [x] Add proper error responses for API calls

## 5. Add prescription/referral creation from EHR
- [x] Add "Create Prescription" button to doctor/ehr/show.blade.php
- [x] Add "Create Referral" button to doctor/ehr/show.blade.php
- [x] Add routes for prescription/referral creation from EHR context
- [x] Update routes/web.php with new routes
- [x] Add controller methods for EHR-based prescription/referral creation

## 6. Clean up redundant code
- [x] Remove unused methods in DoctorEhrController
- [x] Remove redundant code in EhrService
- [ ] Improve code organization and add proper docblocks
- [x] Remove legacy create/store methods that are disabled
