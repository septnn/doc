# -*- coding: utf-8 -*-
#!/usr/bin/env python
import wx
import sys, os, time
import win32api
from win32con import AW_ACTIVATE, AW_BLEND, AW_CENTER, AW_HIDE, AW_HOR_NEGATIVE, AW_HOR_POSITIVE, AW_SLIDE, AW_VER_NEGATIVE, AW_VER_POSITIVE,SPI_GETWORKAREA
from ctypes import windll, c_int


APP_TITLE = u'TDL'
APP_ICON = './favicon.ico'

class mainFrame(wx.Frame):
    '''程序主窗口类，继承自wx.Frame'''
    
    id_open = wx.NewId()
    id_save = wx.NewId()
    id_quit = wx.NewId()
    
    id_help = wx.NewId()
    id_about = wx.NewId()
    
    def __init__(self, parent):
        '''构造函数'''

        wx.Frame.__init__(self, parent, id=-1, title=APP_TITLE)
        # 获得右下坐标
        workarea = win32api.GetMonitorInfo(1)['Work']
        pos=(workarea[2]-280,workarea[3]-180)
        # 设置背景颜色
        self.SetBackgroundColour(wx.Colour(224, 224, 224))
        # 设置大小
        self.SetSize((200, 150))
        # 设置最大大小
        self.SetMaxSize((200, 150))
        # 设置定位
        self.SetPosition(pos)
        # 居中
        # self.Center()
        # 设置icon
        self.SetIcon(wx.Icon(APP_ICON, wx.BITMAP_TYPE_ICO))
        # self.Maximize()
        # 控制最大化，最小化按钮
        # STAY_ON_TOP 强制置顶
        # MINIMIZE_BOX 最小化
        self.SetWindowStyle(wx.DEFAULT_FRAME_STYLE^(wx.RESIZE_BORDER|wx.MAXIMIZE_BOX|wx.CLOSE_BOX)|wx.STAY_ON_TOP)

        # AW_SLIDE 使用滑动类型。缺省为滚动类型。使用AW_CENTER标志时被忽略
        # AW_VER_NEGATIVE 自下向上显示窗口。该标志可以在滚动动画和滑动动画中使用。当使用AW_CENTER标志时，该标志将被忽略
        # AW_ACTIVATE 激活窗口。在使用了AW_HIDE标志后不能使用这个标志
        flags = AW_SLIDE | AW_VER_NEGATIVE | AW_ACTIVATE
        windll.user32.AnimateWindow(c_int(self.GetHandle()), c_int(600), c_int(flags)) # 淡入淡出
        # self.Refresh() # 底部淡出
        # self.Bind(wx.EVT_CLOSE,self.close)

        ws = wx.WrapSizer()

        # self.text = wx.TextCtrl(self, -1, u'', size=(175, -1), style=wx.TE_MULTILINE|wx.TE_PROCESS_ENTER)  # 多文本框
        self.text = wx.TextCtrl(self, -1, u'', size=(175, -1), style=wx.TE_PROCESS_ENTER)  
        self.text.SetInsertionPoint(0)
        self.text.SetFocus()
        ws.Add(self.text)
        self.Bind(wx.EVT_TEXT_ENTER, self.submit)

        print()
        # 创建定时器 
        self.timer = wx.Timer(self)#创建定时器 
        self.Bind(wx.EVT_TIMER, self.OnTimer, self.timer)#绑定一个定时器事件 
        todo = 2 * 60 * 1000
        # todo = 5000
        self.timer.Start(todo)#设定时间间隔为1000毫秒,并启动定时器
        
        
        # self._CreateMenuBar()         # 菜单栏
        # self._CreateToolBar()         # 工具栏
        # self._CreateStatusBar()       # 状态栏
    def OnTimer(self, params):
        self.Iconize(False)
        self.text.SetFocus()
        self.Show()
    def close(self, params):
        self.Hide() # 隐藏
    def submit(self, enter):
        s = enter.GetString()
        print(s)
        # 写入文件
        m = time.strftime('%m',time.localtime(time.time()))
        d = time.strftime('%d',time.localtime(time.time()))
        fileName = m+'.md'
        with open(fileName, 'a+', encoding="utf-8") as file:
            file.write('- ['+d+'] '+s+'\n')
        self.text.Clear()
        self.Iconize(True)
        # self.Hide()

    def _CreateMenuBar(self):
        '''创建菜单栏'''
        
        self.mb = wx.MenuBar()
        
        # 文件菜单
        m = wx.Menu()
        m.Append(self.id_open, u"打开文件")
        m.Append(self.id_save, u"保存文件")
        m.AppendSeparator()
        m.Append(self.id_quit, u"退出系统")
        self.mb.Append(m, u"文件")
        
        self.Bind(wx.EVT_MENU, self.OnOpen, id=self.id_open)
        self.Bind(wx.EVT_MENU, self.OnSave, id=self.id_save)
        self.Bind(wx.EVT_MENU, self.OnQuit, id=self.id_quit)
        
        # 帮助菜单
        m = wx.Menu()
        m.Append(self.id_help, u"帮助主题")
        m.Append(self.id_about, u"关于...")
        self.mb.Append(m, u"帮助")
        
        self.Bind(wx.EVT_MENU, self.OnHelp,id=self.id_help)
        self.Bind(wx.EVT_MENU, self.OnAbout,id=self.id_about)
        
        self.SetMenuBar(self.mb)
    
    def _CreateToolBar(self):
        '''创建工具栏'''
        
        # bmp_open = wx.Bitmap('res/open_16.png', wx.BITMAP_TYPE_ANY) # 请自备按钮图片
        # bmp_save = wx.Bitmap('res/save_16.png', wx.BITMAP_TYPE_ANY) # 请自备按钮图片
        # bmp_help = wx.Bitmap('res/help_16.png', wx.BITMAP_TYPE_ANY) # 请自备按钮图片
        # bmp_about = wx.Bitmap('res/about_16.png', wx.BITMAP_TYPE_ANY) # 请自备按钮图片
        bmp_open = wx.Bitmap(APP_ICON, wx.BITMAP_TYPE_ANY) # 请自备按钮图片
        bmp_save = wx.Bitmap(APP_ICON, wx.BITMAP_TYPE_ANY) # 请自备按钮图片
        bmp_help = wx.Bitmap(APP_ICON, wx.BITMAP_TYPE_ANY) # 请自备按钮图片
        bmp_about = wx.Bitmap(APP_ICON, wx.BITMAP_TYPE_ANY) # 请自备按钮图片
        
        self.tb = wx.ToolBar(self)
        self.tb.SetToolBitmapSize((16,16))
        
        self.tb.AddLabelTool(self.id_open, u'打开文件', bmp_open, shortHelp=u'打开', longHelp=u'打开文件')
        self.tb.AddLabelTool(self.id_save, u'保存文件', bmp_save, shortHelp=u'保存', longHelp=u'保存文件')
        self.tb.AddSeparator()
        self.tb.AddLabelTool(self.id_help, u'帮助', bmp_help, shortHelp=u'帮助', longHelp=u'帮助')
        self.tb.AddLabelTool(self.id_about, u'关于', bmp_about, shortHelp=u'关于', longHelp=u'关于...')
        
        #self.Bind(wx.EVT_TOOL_RCLICKED, self.OnOpen, id=self.id_open)
        
        self.tb.Realize()
    
    def _CreateStatusBar(self):
        '''创建状态栏'''
        
        self.sb = self.CreateStatusBar()
        self.sb.SetFieldsCount(3)
        self.sb.SetStatusWidths([-2, -1, -1])
        self.sb.SetStatusStyles([wx.SB_RAISED, wx.SB_RAISED, wx.SB_RAISED])
        
        self.sb.SetStatusText(u'状态信息0', 0)
        self.sb.SetStatusText(u'', 1)
        self.sb.SetStatusText(u'状态信息2', 2)
    
    def OnOpen(self, evt):
        '''打开文件'''
        
        self.sb.SetStatusText(u'打开文件', 1)
    
    def OnSave(self, evt):
        '''保存文件'''
        
        self.sb.SetStatusText(u'保存文件', 1)
    
    def OnQuit(self, evt):
        '''退出系统'''
        
        self.sb.SetStatusText(u'退出系统', 1)
        self.Destroy()
    
    def OnHelp(self, evt):
        '''帮助'''
        
        self.sb.SetStatusText(u'帮助', 1)
    
    def OnAbout(self, evt):
        '''关于'''
        
        self.sb.SetStatusText(u'关于', 1)
        
class mainApp(wx.App):
    def OnInit(self):
        self.SetAppName(APP_TITLE)
        self.Frame = mainFrame(None)
        self.Frame.Show()
        return True

if __name__ == "__main__":
    app = mainApp()
    app.MainLoop()