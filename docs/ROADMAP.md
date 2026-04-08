# Roadmap & Known Issues

## Known Issues (ปัญหาที่รู้อยู่)

### High Priority

| Issue | Description | Workaround |
|-------|-------------|------------|
| SQLite Lock | เมื่อมีหลาย users พร้อมกัน อาจเกิด database locked | เปลี่ยนใช้ MySQL สำหรับ production |
| Stock Sync | current_stock อาจไม่ตรงกับข้อมูลจริงหลัง edit/delete | รัน `php artisan stock:sync` |
| PDF Thai Font | บาง PDF อาจแสดงภาษาไทยไม่ครบ | ใช้ mPDF แทน DomPDF |
| Large Report | รายงานที่มีข้อมูลมากอาจ timeout | เพิ่ม pagination หรือ export เป็น Excel |

### Medium Priority

| Issue | Description | Status |
|-------|-------------|--------|
| Session Timeout | Session หมดอายุโดยไม่แจ้งเตือน | Planned |
| Mobile Responsive | บางหน้าแสดงผลไม่ดีบน mobile | In Progress |
| Search Performance | ค้นหาช้าเมื่อมีสินค้ามาก | Planned |
| Bulk Import | Import ไฟล์ใหญ่อาจ fail | Planned |

### Low Priority

| Issue | Description |
|-------|-------------|
| Dark Mode | ยังไม่รองรับ dark mode |
| Multi-language | รองรับเฉพาะภาษาไทย |
| Audit Log | ไม่มี log การเปลี่ยนแปลงข้อมูล |

---

## Feature Roadmap

### Phase 1: Core Improvements (Q2 2026)

#### 1.1 Performance Optimization
- [ ] เพิ่ม database indexing สำหรับ search queries
- [ ] Implement query caching สำหรับรายงาน
- [ ] Optimize Eloquent queries (reduce N+1)
- [ ] Add lazy loading สำหรับหน้า list

#### 1.2 User Experience
- [ ] เพิ่ม loading indicators
- [ ] ปรับปรุง form validation feedback
- [ ] เพิ่ม keyboard shortcuts
- [ ] ปรับปรุง mobile responsive

#### 1.3 Stock Management
- [ ] เพิ่ม batch stock adjustment
- [ ] Stock transfer ระหว่างคลัง
- [ ] Stock reservation system
- [ ] เพิ่ม barcode/QR code support

---

### Phase 2: Advanced Features (Q3 2026)

#### 2.1 Reporting & Analytics
- [ ] Dashboard analytics พร้อม charts
- [ ] รายงาน ABC analysis
- [ ] รายงานแนวโน้มสต๊อก (trend)
- [ ] Export รายงานเป็น PDF/Excel ปรับแต่งได้
- [ ] Scheduled report (ส่งอีเมลอัตโนมัติ)

#### 2.2 Integration
- [ ] REST API สำหรับ external systems
- [ ] Webhook notifications
- [ ] Import/Export CSV/Excel ปรับปรุง
- [ ] เชื่อมต่อระบบบัญชี

#### 2.3 Notifications
- [ ] Push notifications (browser)
- [ ] LINE Notify integration
- [ ] SMS notifications
- [ ] Customizable alert rules

---

### Phase 3: Enterprise Features (Q4 2026)

#### 3.1 Multi-tenant & Security
- [ ] Multi-warehouse management
- [ ] Advanced permission system
- [ ] Two-factor authentication (2FA)
- [ ] Audit log & activity tracking
- [ ] Data encryption at rest

#### 3.2 Advanced Inventory
- [ ] Lot/Batch tracking
- [ ] Serial number tracking
- [ ] Expiry date management
- [ ] Multiple units of measure
- [ ] Bill of Materials (BOM)

#### 3.3 Automation
- [ ] Auto reorder points
- [ ] Scheduled stock counts
- [ ] Auto backup scheduling
- [ ] Workflow automation

---

### Phase 4: Future Vision (2027+)

#### 4.1 AI & Machine Learning
- [ ] Demand forecasting
- [ ] Automatic stock optimization
- [ ] Anomaly detection
- [ ] Smart recommendations

#### 4.2 Mobile App
- [ ] Native mobile app (iOS/Android)
- [ ] Offline mode support
- [ ] Barcode scanner integration
- [ ] Voice commands

#### 4.3 Cloud & Scale
- [ ] Cloud deployment option
- [ ] Multi-region support
- [ ] Real-time sync
- [ ] High availability setup

---

## Technical Debt

### Code Quality
| Item | Priority | Effort |
|------|----------|--------|
| เพิ่ม Unit tests coverage | High | Medium |
| Refactor large controllers | Medium | High |
| Extract services จาก controllers | Medium | Medium |
| Update dependencies | Low | Low |
| Remove unused code | Low | Low |

### Infrastructure
| Item | Priority | Effort |
|------|----------|--------|
| ตั้งค่า CI/CD pipeline | High | Medium |
| เพิ่ม automated testing | High | Medium |
| Setup staging environment | Medium | Low |
| Implement proper logging | Medium | Low |
| Add health check endpoints | Low | Low |

### Documentation
| Item | Priority | Effort |
|------|----------|--------|
| API documentation (OpenAPI) | Medium | Medium |
| User manual | Medium | High |
| Video tutorials | Low | High |
| Inline code documentation | Low | Medium |

---

## Version History

### v1.0.0 (Current)
- ระบบจัดการสินค้าและหมวดหมู่
- การรับสินค้าเข้าคลัง
- การเบิกสินค้าออก
- รายงานพื้นฐาน
- ระบบแจ้งเตือนสต๊อก
- Backup ระบบ
- Authentication & Authorization

### Planned Releases

#### v1.1.0 (Next)
- Performance improvements
- Mobile responsive fixes
- Bug fixes

#### v1.2.0
- Dashboard analytics
- Advanced reports
- Barcode support

#### v2.0.0
- REST API
- Multi-warehouse
- Advanced permissions

---

## Contributing

### How to Contribute

1. **Report bugs** - สร้าง issue ใน repository
2. **Suggest features** - เปิด discussion หรือ issue
3. **Submit PR** - Fork และส่ง pull request

### Development Guidelines

1. Follow [Coding Standards](./CODING_STANDARDS.md)
2. Write tests สำหรับ features ใหม่
3. Update documentation
4. ใช้ conventional commits

### Priority Labels

| Label | Description |
|-------|-------------|
| `critical` | ต้องแก้ไขทันที |
| `high` | สำคัญมาก แก้ไขใน sprint ถัดไป |
| `medium` | สำคัญปานกลาง |
| `low` | Nice to have |
| `wontfix` | ไม่แก้ไข |

---

## Feedback & Contact

- **Issues**: GitHub Issues
- **Email**: dev@example.com
- **Documentation**: `/docs` folder

---

*Last updated: March 2026*
