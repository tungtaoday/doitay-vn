# Automate the AI Agent Workflow – DoiTay.vn

Mục tiêu: Chuyển luồng agent AI thủ công (CLI) sang tự động hóa, an toàn, có thể mở rộng, vận hành 24/7.

## 1) Kiến trúc mục tiêu (tối giản → mở rộng)

```
Minimal (không MQ)                             Full (có MQ)
┌───────────────┐                              ┌───────────────┐
│ Orchestrator  │──(APScheduler)──► Jobs      │ Orchestrator  │──► Enqueue Jobs
└───────┬───────┘                              └───────┬───────┘
        │                                          ┌───┴──────────────┐
        ├─ ResearchAgent.run()                    │ Redis/RabbitMQ    │
        ├─ ContentCreator.run()                   └───┬──────────────┘
        ├─ VisualDesigner.run()                       │
        ├─ SocialMediaManager.run()                   │
        └─ AnalyticsAgent.run()                 ┌─────┴─────┐  ┌──────┴─────┐  ┌────────┴────────┐
                                               │ Research  │  │ Content   │  │ Visual/Publish  │
                                               │ Worker    │  │ Worker    │  │ Worker          │
                                               └───────────┘  └───────────┘  └──────────────────┘
```

Khuyến nghị: Bắt đầu Minimal (APScheduler + Orchestrator tuần tự) để lên tự động nhanh. Khi tăng tải → chuyển Full (Redis+RQ/Dramatiq) để scale.

## 2) Thư mục & file chuẩn hoá

```
facebook_content_agency/
  .env
  orchestrator.py           # Điều phối theo state machine
  scheduler.py              # Lịch tự động (APScheduler)
  queues.py                 # Adapter MQ (tùy chọn)
  tasks/
    research.py             # ResearchAgent
    content.py              # ContentCreator
    visual.py               # VisualDesigner
    social.py               # SocialMediaManager (Slack/Facebook)
    analytics.py            # AnalyticsAgent
  integrations/
    slack_client.py         # SlackApproval/SlackNotify
    facebook_client.py      # Graph API (photos/feed)
  utils/
    logger.py               # JSON logs
    storage.py              # Lưu output đúng cấu trúc cũ
    config.py               # Đọc .env + constants
  outputs/                  # Giữ cấu trúc log như CLI
    YYYY-MM-DD/Agent/HHMMSS.txt
```

## 3) Cấu hình (.env mẫu)

```
TIMEZONE=Asia/Ho_Chi_Minh
SCHEDULE_WINDOWS=07:30,12:00,19:30

# Slack
SLACK_WEBHOOK_URL=...
SLACK_BOT_TOKEN=xoxb-...
SLACK_APPROVAL_CHANNEL=#social-approvals

# Facebook
FB_PAGE_ID=...
FB_PAGE_ACCESS_TOKEN=...

# Queue (tùy chọn)
REDIS_URL=redis://localhost:6379/0
```

## 4) Orchestrator (state machine tối giản)

```python
# orchestrator.py
from utils.config import cfg
from utils.storage import save_agent_output
from utils.logger import log
from tasks import research, content, visual, social, analytics

class PipelineState:
    def __init__(self, campaign):
        self.campaign = campaign
        self.ctx = {}

    def run(self):
        self.step_research()
        self.step_content()
        self.step_visual()
        self.step_approval()
        self.step_publish()
        self.step_analytics()

    def step_research(self):
        out = research.run(self.campaign)
        save_agent_output('ResearchAgent', out)
        self.ctx['research'] = out
        log('research_done', out)

    def step_content(self):
        out = content.run(self.campaign, self.ctx.get('research'))
        save_agent_output('ContentCreator', out)
        self.ctx['content'] = out
        log('content_done', out)

    def step_visual(self):
        out = visual.run(self.campaign, self.ctx.get('content'))
        save_agent_output('VisualDesigner', out)
        self.ctx['visual'] = out
        log('visual_done', out)

    def step_approval(self):
        out = social.slack_approval(self.ctx['content'], self.ctx['visual'])
        save_agent_output('SocialMediaManager', out)
        self.ctx['approval'] = out
        log('approval_submitted', out)

    def step_publish(self):
        out = social.publish_facebook(self.ctx['content'], self.ctx['visual'])
        save_agent_output('SocialMediaManager', out)
        self.ctx['publish'] = out
        log('publish_done', out)

    def step_analytics(self):
        out = analytics.plan_kpis(self.campaign)
        save_agent_output('AnalyticsAgent', out)
        self.ctx['analytics'] = out
        log('analytics_ready', out)
```

## 5) Scheduler (APScheduler – Windows friendly)

```python
# scheduler.py
from apscheduler.schedulers.blocking import BlockingScheduler
from orchestrator import PipelineState
from utils.logger import log

scheduler = BlockingScheduler(timezone='Asia/Ho_Chi_Minh')

@scheduler.scheduled_job('cron', hour='7,12,19', minute='30')
def kick_daily_campaign():
    campaign = {
        'title': 'Tuyển thợ điện Thanh Xuân',
        'goal': 'Leads thợ đăng ký',
        'audience': 'Thợ điện tại Hà Nội/Thanh Xuân',
        'pillar': 'tips+testimonial'
    }
    log('cron_start', campaign)
    PipelineState(campaign).run()

if __name__ == '__main__':
    scheduler.start()
```

Chạy nền (PowerShell):
```
py -3.10-64 scheduler.py
```

## 6) Tasks (skeleton bám theo CLI cũ)

```python
# tasks/research.py
from utils.storage import save_agent_output

def run(campaign):
    # TODO: lấy trend, hashtag an toàn, đối thủ/audience
    return {
        'trends': ['thợ điện', 'sửa điện nhanh'],
        'hashtags_safe': ['#DoiTay','#DoiTayVN','#ThoViet','#KetNoiTho','#HaNoi','#ThanhXuan','#ThoDien'],
        'insights': {'best_time': ['07:30','19:30']}
    }
```

```python
# tasks/content.py
from utils.config import build_utm

def run(campaign, research_ctx):
    utm = build_utm(campaign['title'])
    caption = f"Hook mạnh. Lợi ích thực tế. Đăng ký tại {utm}"
    hashtags = research_ctx['hashtags_safe'] + ['#SuaDien','#LapDatDien']
    return {
        'caption_final': caption,
        'caption_variants': [caption+' (A)', caption+' (B)'],
        'hashtags': ' '.join(hashtags)
    }
```

```python
# tasks/visual.py

def run(campaign, content_ctx):
    return {
        'image_concept': 'Thợ điện làm việc tại Thanh Xuân; overlay 6-10 từ',
        'alt_text': 'Thợ điện Thanh Xuân – DoiTay.vn',
        'image_url': 'PLACEHOLDER'
    }
```

```python
# tasks/social.py
from integrations.slack_client import slack_approval
from integrations.facebook_client import post_photo, post_feed

def slack_approval(content_ctx, visual_ctx):
    return slack_approval(
        caption=content_ctx['caption_final'],
        image_url=visual_ctx['image_url'],
        hashtags=content_ctx['hashtags']
    )

def publish_facebook(content_ctx, visual_ctx):
    if visual_ctx['image_url'] and visual_ctx['image_url'] != 'PLACEHOLDER':
        return post_photo(url=visual_ctx['image_url'], caption=f"{content_ctx['caption_final']}\n\n{content_ctx['hashtags']}")
    return post_feed(message=f"{content_ctx['caption_final']}\n\n{content_ctx['hashtags']}")
```

```python
# tasks/analytics.py

def plan_kpis(campaign):
    return {
        'kpis': ['Reach','CTR','Comments','Shares','Saves','CPL'],
        'checklist': ['CTA+UTM','Hashtag chuẩn','Khung giờ hiệu quả','So sánh A/B']
    }
```

## 7) Slack & Facebook integrations (chuẩn hoá)

```python
# integrations/slack_client.py
import requests, os

WEBHOOK = os.getenv('SLACK_WEBHOOK_URL')
CHANNEL = os.getenv('SLACK_APPROVAL_CHANNEL')

def slack_approval(caption, image_url, hashtags):
    payload = {
        'channel': CHANNEL,
        'text': 'Approval request – DoiTay.vn',
        'blocks': [
            {'type':'section','text':{'type':'mrkdwn','text':f"*Caption:*\n{caption}"}},
            {'type':'section','text':{'type':'mrkdwn','text':f"*Hashtags:*\n{hashtags}"}},
            {'type':'image','image_url': image_url or 'https://doitay.vn/public/default.png','alt_text':'preview'}
        ]
    }
    r = requests.post(WEBHOOK, json=payload, timeout=10)
    r.raise_for_status()
    # Giả lập token phản hồi
    return { 'status':'submitted', 'token':'APPROVAL_TOKEN_PLACEHOLDER' }
```

```python
# integrations/facebook_client.py
import requests, os

PAGE_ID = os.getenv('FB_PAGE_ID')
TOKEN = os.getenv('FB_PAGE_ACCESS_TOKEN')
BASE = 'https://graph.facebook.com/v18.0'

def post_photo(url, caption):
    res = requests.post(f"{BASE}/{PAGE_ID}/photos", data={
        'url': url,
        'caption': caption,
        'access_token': TOKEN
    }, timeout=20)
    return res.json()

def post_feed(message):
    res = requests.post(f"{BASE}/{PAGE_ID}/feed", data={
        'message': message,
        'access_token': TOKEN
    }, timeout=20)
    return res.json()
```

## 8) Windows runbook (không cần Docker/MQ ban đầu)

1) Tạo venv + cài đặt
```
py -3.10-64 -m venv .venv
.\.venv\Scripts\activate
pip install requests apscheduler python-dotenv
```
2) Tạo `.env` theo mẫu và cập nhật token/ID
3) Chạy tự động theo lịch
```
py -3.10-64 scheduler.py
```
4) Chạy 1 chiến dịch tức thời
```
py -3.10-64 orchestrator.py
```
5) (Tuỳ chọn) Dùng Windows Task Scheduler để khởi động `scheduler.py` khi login/boot

## 9) Nâng cấp lên Full (Redis + RQ/Dramatiq)

- Cài Redis (hoặc dùng Docker/WSL). Trên Windows có thể dùng Memurai/Valkey.
- Thay các hàm `run()` bằng job `queue.enqueue()`; mỗi agent là 1 worker process.
- Orchestrator chỉ điều phối state + enqueue + chờ kết quả (polling/Redis pubsub).
- Retry & backoff: 3 lần, 5s/15s/45s.

## 10) Mapping từ CLI thủ công → Tự động

- ResearchAgent: gọi `research.run()` theo lịch/trigger → lưu outputs/...
- ContentCreator: `content.run()` nhận context Research → lưu outputs/...
- VisualDesigner: `visual.run()` tạo concept/PLACEHOLDER image_url → outputs/...
- SocialMediaManager: `slack_approval()` rồi `publish_facebook()` khi sẵn sàng
- AnalyticsAgent: `plan_kpis()` + nightly pull (sau mở rộng)
- ResultLogger cũ = `save_agent_output(Agent, dict)`

## 11) Observability & QA

- Logging JSON theo event: research_done, content_done, visual_done, approval_submitted, publish_done, analytics_ready.
- Guardrails tự động: kiểm CTA+UTM, hashtag chuẩn, overlay 6–10 từ trước khi publish.
- Alert Slack khi lỗi publish/approval.

## 12) Lộ trình triển khai (2 tuần)

- Ngày 1–2: Chuẩn hóa repo, .env, skeleton tasks, orchestrator, scheduler
- Ngày 3–4: Tích hợp SlackNotify/Approval (webhook), Facebook publish (feed/photos)
- Ngày 5–6: QA checklist, logs, outputs như CLI
- Tuần 2: A/B caption, khung giờ linh hoạt, retry/backoff, Task Scheduler + hướng dẫn vận hành
- Sau go-live: cân nhắc MQ để scale, thêm dashboard + nightly analytics

---

Ghi chú: Blueprint giữ nguyên cấu trúc lưu file `outputs/YYYY-MM-DD/<Agent>/HHMMSS.txt` để không phá vỡ quy trình hiện tại, đồng thời mở đường nâng cấp lên hàng đợi và nhiều worker khi tăng tải.

---

## Phủ toàn bộ pipeline core và vị trí bước "lập kế hoạch"

Pipeline core (Research → Content → Visual → Approval → Publish → Analytics) đã được bao phủ và map như sau:
- Research: mục 6 "Tasks" → `tasks/research.py` + gọi trong Orchestrator `step_research()`
- Content: mục 6 → `tasks/content.py` + `step_content()`
- Visual: mục 6 → `tasks/visual.py` + `step_visual()`
- Approval (Slack): mục 7 → `integrations/slack_client.py` + `step_approval()`
- Publish (Facebook API): mục 7 → `integrations/facebook_client.py` + `step_publish()`
- Analytics (KPI khuyến nghị): mục 6 → `tasks/analytics.py` + `step_analytics()`

"Lập kế hoạch" (planning) nằm ở lớp chiến lược trước pipeline core, để tạo campaign/campaign calendar làm đầu vào cho Orchestrator/Scheduler. Bổ sung dưới đây.

### 0) Strategic Planning Layer (Annual/Quarterly/Monthly → Weekly → Daily)

Mục đích: sinh kế hoạch (OKR/KPI, content calendar, campaign definitions) rồi feed vào Scheduler/Orchestrator.

Thành phần đề xuất:
- `tasks/planning.py`: sinh danh sách chiến dịch và khung giờ/nhóm audience/UTM slug
- `planning_store.json` (hoặc DB): lưu kế hoạch để Scheduler đọc hàng ngày
- `scheduler.py`: có thêm job weekly/monthly để refresh kế hoạch

Ví dụ mã:
```python
# tasks/planning.py
from utils.storage import save_agent_output
from datetime import datetime, timedelta

def generate_month_plan(month_context=None):
    # month_context có thể chứa OKR/KPI, ngân sách, ưu tiên khu vực/ngành
    campaigns = [
        {
            'title': 'Tuyển thợ điện Thanh Xuân - Tuần 1',
            'goal': 'Leads thợ đăng ký',
            'audience': 'Thợ điện tại Hà Nội/Thanh Xuân',
            'pillar': 'tips+testimonial',
            'windows': ['07:30','12:00','19:30']
        },
        {
            'title': 'Tuyển thợ nước Hà Nội - Tuần 1',
            'goal': 'Leads thợ đăng ký',
            'audience': 'Thợ nước tại Hà Nội',
            'pillar': 'case+howto',
            'windows': ['07:30','19:30']
        }
    ]
    plan = { 'month': datetime.now().strftime('%Y-%m'), 'campaigns': campaigns }
    save_agent_output('ContentManager', plan, logical_group='Planning')
    return plan
```

Hook vào Scheduler:
```python
# scheduler.py (bổ sung job lập kế hoạch)
from tasks import planning

@scheduler.scheduled_job('cron', day='1', hour='8', minute='0')
def monthly_planning():
    # Sinh kế hoạch đầu tháng
    plan = planning.generate_month_plan()
    # Có thể ghi ra JSON để orchestrator/daily job đọc
    # utils.storage.save_json('outputs/planning/current_month.json', plan)

@scheduler.scheduled_job('cron', day_of_week='mon', hour='8', minute='0')
def weekly_sync_planning():
    # Đồng bộ/điều chỉnh kế hoạch mỗi thứ Hai
    planning.generate_month_plan()
```

Kết nối với Orchestrator (daily run):
```python
# orchestrator.py (đơn giản: dùng 1 campaign mẫu hoặc load từ plan)
def pick_today_campaign():
    # TODO: đọc từ outputs/planning/current_month.json theo ngày/ưu tiên
    return {
        'title': 'Tuyển thợ điện Thanh Xuân - Hôm nay',
        'goal': 'Leads thợ đăng ký',
        'audience': 'Thợ điện tại Hà Nội/Thanh Xuân',
        'pillar': 'tips+testimonial'
    }

# scheduler.py (job daily sử dụng pick_today_campaign)
@scheduler.scheduled_job('cron', hour='7,12,19', minute='30')
def kick_daily_campaign():
    from orchestrator import PipelineState, pick_today_campaign
    campaign = pick_today_campaign()
    PipelineState(campaign).run()
```

Như vậy, runbook hiện đã:
- Bao phủ đầy đủ pipeline core bằng các bước `step_*` tương ứng
- Có lịch tự động (cron 3 khung giờ/ngày)
- Có Approval Slack + Publish Facebook
- Có lưu vết/logs + KPI gợi ý trong `tasks/analytics.py`
- Và đã xác định rõ vị trí "lập kế hoạch" ở lớp chiến lược (jobs monthly/weekly) trước khi feed vào daily pipeline.
